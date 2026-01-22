<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Models/CartaDiCredito.php';
require_once __DIR__ . '/Models/Ordine.php';
require_once __DIR__ . '/Models/Utente.php';

use App\Models\CartaDiCredito;
use App\Models\Ordine;
use App\Models\Utente;

session_start();

$userRole = $_SESSION['UserRole'] ?? null;
$_SESSION['return_to'] = $_SERVER['REQUEST_URI'];

if (!isset($_SESSION['LoggedUser']['id'])) {
	header('Location: login.php');
	exit;
}

$datiUtente = Utente::where('id', $_SESSION['LoggedUser']['id'])->first();
if ($datiUtente === null) {
	echo '<div class="alert alert-danger">Errore: dati utente non trovati.</div>';
	exit;
}

$orders = Ordine::where('id_utente', $datiUtente->id)
	->orderBy('id', 'desc')
	->get();

$savedCards = CartaDiCredito::where('id_utente', $datiUtente->id)->get()->map(function ($card) {
	return [
		'id' => $card->id,
		'card_owner' => $card->nome_titolare,
		'codice_carta' => $card->codice_carta,
		'circuito_pagamento' => $card->circuito_pagamento,
		'scadenza_mese' => str_pad($card->scadenza_mese, 2, '0', STR_PAD_LEFT),
		'scadenza_anno' => $card->scadenza_anno,
	];
})->toArray();
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Carte e pagamenti - Deja-brew</title>
	<link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
	<link rel="stylesheet" href="./dist/bootstrap5/icons/bootstrap-icons.css">
	<link rel="stylesheet" href="./dist/custom/css/new-style.css">
</head>
<body class="bg-cream">
	<?php require_once __DIR__ . '/navbar-selector.php'; ?>

	<div class="container py-4">
		<div class="d-flex align-items-center justify-content-between flex-wrap gap-2 mb-4">
			<div>
				<p class="text-muted mb-1 small">Gestisci i tuoi metodi di pagamento</p>
				<h1 class="h3 text-primary-brown mb-0">Carte e pagamenti</h1>
			</div>
			<div class="badge bg-light text-primary-brown border">Account: <?= htmlspecialchars($datiUtente->username ?? $datiUtente->email) ?></div>
		</div>

		<?php if (isset($_SESSION['errors'])): ?>
			<?php foreach ($_SESSION['errors'] as $error): ?>
				<div class="alert alert-danger shadow-sm border-0"><?= $error ?></div>
			<?php endforeach; unset($_SESSION['errors']); ?>
		<?php endif; ?>

		<?php if (isset($_SESSION['success'])): ?>
			<div class="alert alert-success shadow-sm border-0"><?= $_SESSION['success'] ?></div>
			<?php unset($_SESSION['success']); ?>
		<?php endif; ?>

		<div class="row g-4">
			<div class="col-lg-7">
				<div class="card shadow-sm border-0 h-100">
					<div class="card-header bg-primary-brown text-white d-flex align-items-center">
						<i class="bi bi-clipboard-check me-2"></i>Riepilogo ordini
					</div>
					<div class="card-body">
						<?php if ($orders->isEmpty()): ?>
							<div class="text-center text-muted py-4">
								<i class="bi bi-bag-x fs-1 d-block mb-2"></i>
								<p class="mb-0">Non hai ancora effettuato ordini.</p>
							</div>
						<?php else: ?>
							<div class="table-responsive">
								<table class="table align-middle mb-0">
									<thead class="table-light">
										<tr>
											<th scope="col">Venditore</th>
											<th scope="col">Prodotto</th>
											<th scope="col">Status</th>
											<th scope="col" class="text-end">Totale</th>
										</tr>
									</thead>
									<tbody>
										<?php foreach ($orders as $order): ?>
											<tr>
												<td><?= htmlspecialchars($order->prodotto->venditore->user->username ?? $order->prodotto->venditore->nome ?? '') ?></td>
												<td>
													<?php if ($order->prodotto): ?>
														<a href="./product.php?id=<?= $order->prodotto->id ?>" class="text-decoration-none text-primary-brown fw-semibold">
															<?= htmlspecialchars($order->prodotto->nome) ?>
														</a>
													<?php else: ?>
														<span class="text-muted">Prodotto</span>
													<?php endif; ?>
												</td>
												<td>
													<span class="badge bg-secondary text-white text-uppercase"><?= htmlspecialchars($order->status ?? 'n/d') ?></span>
												</td>
												<td class="text-end text-secondary-red fw-semibold">
													€ <?= number_format((float) $order->prezzo_totale, 2, ',', '.') ?>
												</td>
											</tr>
										<?php endforeach; ?>
									</tbody>
								</table>
							</div>
						<?php endif; ?>
					</div>
				</div>
			</div>

			<div class="col-lg-5">
				<div class="card shadow-sm border-0 mb-4">
					<div class="card-header bg-light d-flex align-items-center justify-content-between">
						<span class="fw-semibold text-primary-brown"><i class="bi bi-credit-card me-2"></i>Carte salvate</span>
						<span class="badge bg-secondary text-white"><?= count($savedCards) ?></span>
					</div>
					<div class="card-body">
						<?php if (empty($savedCards)): ?>
							<div class="text-center text-muted py-3">Nessuna carta salvata.</div>
						<?php else: ?>
							<div class="list-group list-group-flush">
								<?php foreach ($savedCards as $card): ?>
									<div class="list-group-item d-flex align-items-center justify-content-between px-0">
										<div>
											<div class="fw-semibold text-primary-brown"><?= htmlspecialchars($card['card_owner']) ?></div>
											<small class="text-muted d-block"><?= htmlspecialchars($card['circuito_pagamento']) ?> · •••• <?= substr($card['codice_carta'], -4) ?></small>
											<small class="text-muted">Scadenza <?= $card['scadenza_mese'] ?>/<?= $card['scadenza_anno'] ?></small>
										</div>
										<button type="button"
												class="btn btn-outline-primary-custom btn-sm"
												data-card-id="<?= $card['id'] ?>"
												data-circuito="<?= $card['circuito_pagamento'] ?>"
												data-scadenzamese="<?= $card['scadenza_mese'] ?>"
												data-scadenzaanno="<?= $card['scadenza_anno'] ?>"
												data-codicecarta="<?= $card['codice_carta'] ?>"
												data-bs-toggle="modal"
												data-bs-target="#modalModificaCarta">
											Modifica
										</button>
									</div>
								<?php endforeach; ?>
							</div>
						<?php endif; ?>
					</div>
				</div>

				<div class="card shadow-sm border-0">
					<div class="card-header bg-primary-brown text-white">
						<i class="bi bi-plus-circle me-2"></i>Aggiungi nuova carta
					</div>
					<div class="card-body">
						<form action="actions/add_card.php" method="POST" class="row g-3">
							<div class="col-12">
								<label for="card_owner" class="form-label">Nome intestatario</label>
								<input type="text" id="card_owner" name="card_owner" class="form-control" required>
							</div>
							<div class="col-12">
								<label for="card_number" class="form-label">Numero carta</label>
								<input type="text" id="card_number" name="card_number" class="form-control" placeholder="1234 5678 9012 3456" inputmode="numeric" required>
							</div>
							<div class="col-12">
								<label for="circuito_pagamento" class="form-label">Circuito</label>
								<select id="circuito_pagamento" name="circuito_pagamento" class="form-select" required>
									<option value="">Seleziona</option>
									<option value="Visa">Visa</option>
									<option value="MasterCard">MasterCard</option>
									<option value="American Express">American Express</option>
									<option value="Maestro">Maestro</option>
								</select>
							</div>
							<div class="col-md-6">
								<label for="scadenza" class="form-label">Scadenza</label>
								<input type="month" id="scadenza" name="scadenza" class="form-control" required>
							</div>
							<div class="col-md-6">
								<label for="cvv" class="form-label">CVV</label>
								<input type="text" id="cvv" name="cvv" class="form-control" placeholder="123" inputmode="numeric" required>
							</div>
							<div class="col-12 d-grid">
								<button type="submit" class="btn btn-primary-custom">Salva metodo di pagamento</button>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modalModificaCarta" tabindex="-1" aria-labelledby="modalModificaCartaLabel" aria-hidden="true">
		<div class="modal-dialog">
			<div class="modal-content">
				<form id="formModificaCarta" action="actions/update_card.php" method="POST">
					<div class="modal-header">
						<h5 class="modal-title" id="modalModificaCartaLabel">Modifica carta di credito</h5>
						<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
					</div>
					<input type="hidden" id="modal-card_id" name="card_id">
					<div class="modal-body">
						<div class="mb-3">
							<label for="modal-circuito_pagamento" class="form-label">Circuito della carta</label>
							<select id="modal-circuito_pagamento" name="circuito_pagamento" class="form-select" required>
								<option value="">Seleziona</option>
								<option value="Visa">Visa</option>
								<option value="MasterCard">MasterCard</option>
								<option value="American Express">American Express</option>
								<option value="Maestro">Maestro</option>
							</select>
						</div>
						<div class="mb-3">
							<label for="modal-codice_carta" class="form-label">Numero carta</label>
							<input type="text" id="modal-codice_carta" name="codice_carta" class="form-control" placeholder="1234 5678 9012 3456" required pattern="\d{16}">
						</div>
						<div class="mb-3">
							<label for="modal-scadenza" class="form-label">Data scadenza</label>
							<input type="month" id="modal-scadenza" name="scadenza" class="form-control" required>
						</div>
						<div class="mb-3">
							<label for="modal-cvv" class="form-label">CVV</label>
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
		<?php require_once __DIR__ . '/reusables/footer.php' ?>

	<script src="./dist/bootstrap5/js/bootstrap.bundle.min.js"></script>
	<script src="./dist//custom//js/cart-manager.js"></script>
	<script>
		const scadenzaInput = document.getElementById('scadenza');
		if (navigator.userAgent.toLowerCase().indexOf('firefox') > -1) {
			scadenzaInput.setAttribute('placeholder', 'YYYY-MM');
		} else {
			scadenzaInput.setAttribute('type', 'month');
		}

		const modal = document.getElementById('modalModificaCarta');
		modal.addEventListener('show.bs.modal', event => {
			const button = event.relatedTarget;
			modal.querySelector('#modal-card_id').value = button.dataset.cardId;
			modal.querySelector('#modal-circuito_pagamento').value = button.dataset.circuito;
			const month = button.dataset.scadenzamese;
			const year = button.dataset.scadenzaanno;
			modal.querySelector('#modal-scadenza').value = `${year}-${month}`;
			modal.querySelector('#modal-codice_carta').value = button.dataset.codicecarta;
		});

		<?php if(isset($userRole) && ($userRole === Role::BUYER->value)): ?>
			updateCartCount();
		<?php endif; ?>
	</script>
</body>
</html>