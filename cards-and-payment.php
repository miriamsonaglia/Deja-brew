<!DOCTYPE html>
<html lang="it">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    	<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
		<title>Carte e Pagamentiv</title>
		<?php
			

			require_once __DIR__ . '/bootstrap.php';
			require_once __DIR__ . '/Models/CartaDiCredito.php';
			require_once __DIR__ . '/Models/Utente.php';
			use App\Models\Utente;
			use App\Models\CartaDiCredito;

			session_start();

			// --- Controllo utente loggato ---
			$datiUtente = Utente::where('id', $_SESSION['LoggedUser']['id'])->first();
			if ($datiUtente === null) {
				// Handle missing user data: redirect or show error
				echo '<div class="alert alert-danger">Errore: dati utente non trovati.</div>';
				exit;
			}

			// Mock order data (replace with database query)
			$orders = [
				['id' => 1, 'item' => 'Espresso', 'price' => 2.50, 'quantity' => 2],
				['id' => 2, 'item' => 'Cappuccino', 'price' => 3.50, 'quantity' => 1],
			];
			$selected_card = 0;
			$total = array_sum(array_map(fn($o) => $o['price'] * $o['quantity'], $orders));

			// Load saved cards from database
			$savedCards = CartaDiCredito::where('id_utente', $datiUtente->id)->get()->map(function($card) {
				return [
					'id' => $card->id,
					//'card_owner' => $card->nome_titolare,
					'card_owner' => "PlaceHolder", //TODO da modificare quando verrà aggiunta la colonna nome_titolare nel database
					'codice_carta' => $card->codice_carta,
					'circuito_pagamento' => $card->circuito_pagamento,
					'scadenza_mese' => "05", //TODO da modificare quando verrà aggiunta la colonna data nel database
					'scadenza_anno' => "2025", //TODO da modificare quando verrà aggiunta la colonna data nel database

					//'scadenza_mese' => $card->scadenza_mese,
					//'scadenza_anno' => $card->scadenza_anno,
				];
			})->toArray();
		?>
		
	</head>
	<body>
		<?php require_once __DIR__ . '/navbar-selector.php'; ?>
		<div class="container">
			<!-- Order Review -->
			<div class="card">
				<h2>📋 Order Review</h2>
				<?php foreach ($orders as $order): ?>
					<div class="order-item">
						<span><?= htmlspecialchars($order['item']) ?> x<?= $order['quantity'] ?></span>
						<span>$<?= number_format($order['price'] * $order['quantity'], 2) ?></span>
					</div>
				<?php endforeach; ?>
				<div class="total">Total: $<?= number_format($total, 2) ?></div>
			</div>

			<!-- Payment Form -->
			<div class="card">
				<h2>💳 Payment Method</h2>
				<?php
				if (isset($_SESSION['errors'])) {
					foreach ($_SESSION['errors'] as $error) {
						echo "<div class='alert alert-danger'>$error</div>";
					}
					unset($_SESSION['errors']);
				}
				if (isset($_SESSION['success'])){
					echo "<div class='alert alert-success'>{$_SESSION['success']}</div>";
					unset($_SESSION['success']);
				}
				?>
				
				<!-- Saved Cards -->
				<h3>Your Saved Cards</h3>
				<div class="cards-list">
					<?php if (empty($savedCards)): ?>
						<p>No saved cards yet.</p>
					<?php else: ?>
						<?php foreach ($savedCards as $card): ?>
							<div class="card-item">
								<button type="button" class="card-button edit-card-btn" 
										data-card-id="<?= $card['id'] ?>" 
										data-circuito="<?= $card['circuito_pagamento'] ?>" 
										data-scadenzamese="<?= $card['scadenza_mese'] ?>" 
										data-scadenzaanno="<?= $card['scadenza_anno'] ?>" 
										data-codicecarta="<?= $card['codice_carta'] ?>" 
										data-bs-toggle="modal" data-bs-target="#modalModificaCarta">
										Edit Card
								</button>
								<label for="card_<?= $card['id'] ?>">
									<?=htmlspecialchars($card['card_owner']) ?> | <?=$card['circuito_pagamento'] ?> | <?=$card['codice_carta'] ?> | Scadenza <?=$card['scadenza_mese'] ?>-<?=$card['scadenza_anno'] ?>
								</label>
							</div>
						<?php endforeach; ?>
					<?php endif; ?>
				</div>

				<!-- Add New Card Form -->
				<h3>Add New Card</h3>
				<form action="actions/add_card.php" method="POST">
					<div class="form-group">
						<label>Cardholder Name</label>
						<input type="text" name="card_owner" required>
					</div>

					<div class="form-group">
						<label>Numero Carta</label>
						<input type="text" name="card_number" placeholder="1234 5678 9012 3456" required>

						<label>Circuito</label>
						<select id="circuito_pagamento" name="circuito_pagamento" class="form-select" required>
						<option value="">Seleziona</option>
						<option value="Visa">Visa</option>
						<option value="MasterCard">MasterCard</option>
						<option value="American Express">American Express</option>
						<option value="Maestro">Maestro</option>
						</select>
					</div>

					<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
						<div class="form-group">
							<label>Scadenza Carta</label>
							<input type="month" id="scadenza" name="scadenza" required>
						</div>
						<div class="form-group">
							<label>CVV</label>
							<input type="text" name="cvv" placeholder="123" required>
						</div>
					</div>

					<button type="submit">Add Payment Method</button>
				</form>
			</div>
		</div>

		<div class="modal fade" id="modalModificaCarta" tabindex="-1" aria-labelledby="modalModificaCartaLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				<form id="formModificaCarta" action="actions/update_card.php" method="POST">	
				<div class="modal-header">
					<h5 class="modal-title" id="modalModificaCartaLabel">Modifica la carta di credito selezionata</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
					</div>
					<input type="hidden" id="modal-card_id" name="card_id">
					<div class="modal-body">
					<div class="mb-3">
						<label for="circuito_pagamento" class="form-label">Circuito della carta</label>
						<select id="modal-circuito_pagamento" name="circuito_pagamento" class="form-select" required>
						<option value="">Seleziona</option>
						<option value="Visa">Visa</option>
						<option value="MasterCard">MasterCard</option>
						<option value="American Express">American Express</option>
						<option value="Maestro">Maestro</option>
						</select>
					</div>
					<div class="mb-3">
						<label for="codice_carta" class="form-label">Numero carta</label>
						<input type="text" id="modal-codice_carta" name="codice_carta" class="form-control" placeholder="1234 5678 9012 3456" required pattern="\d{16}">
					</div>
					<div class="mb-3">
						<label for="scadenza" class="form-label">Data scadenza</label>
						<input type="month" id="modal-scadenza" name="scadenza" class="form-control" required>
					</div>
					<div class="mb-3">
						<label for="cvvNuova" class="form-label">CVV</label>
						<input type="password" id="modal-cvv" name="cvv_carta" class="form-control" placeholder="***" required pattern="\d{3,4}">
					</div>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Chiudi</button>
						<button type="submit" formaction="actions/update_card.php" class="btn btn-success">Modifica carta</button>
						<button type="submit" formaction="actions/delete_card.php" class="btn btn-danger" onclick="return confirm('Sei sicuro di voler eliminare questa carta?')">Elimina carta</button>
					</div>
				</form>
				</div>
			</div>
		</div>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

		<script>

			const scadenzaInput = document.getElementById('scadenza');

			if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
				// Firefox doesn't support month picker UI
				scadenzaInput.setAttribute('placeholder', 'YYYY-MM'); // guide the user
				// Optional: attach a JS month picker library here
			} else {
				scadenzaInput.setAttribute('type', 'month'); // other browsers show calendar UI
			}

			const modal = document.getElementById('modalModificaCarta');
			modal.addEventListener('show.bs.modal', event => {

				const button = event.relatedTarget;

				modal.querySelector('#modal-card_id').value = button.dataset.cardId;
				modal.querySelector('#modal-circuito_pagamento').value = button.dataset.circuito;
				
    			//console.log(button.dataset);
				//very important, the button data-* sets all the fields names as lowercase and doesn't work with -, _ or / as separators
				
				const month = button.dataset.scadenzamese;
				const year = button.dataset.scadenzaanno;
				modal.querySelector('#modal-scadenza').value = `${year}-${month}`;
				modal.querySelector('#modal-codice_carta').value = button.dataset.codicecarta;

			});

			


		</script>
	</body>
</html>