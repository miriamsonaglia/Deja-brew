<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<title>Payment - Deja Brew</title>
		<?php
			session_start();
						
						if(!isset($_SESSION['LoggedUser']['id'])){
							header("Location: login.php");
							exit;
						}

			require_once __DIR__ . '/bootstrap.php';
			require_once __DIR__ . '/Models/CartaDiCredito.php';
			use App\Models\CartaDiCredito;

			// Mock order data (replace with database query)
			$orders = [
				['id' => 1, 'item' => 'Espresso', 'price' => 2.50, 'quantity' => 2],
				['id' => 2, 'item' => 'Cappuccino', 'price' => 3.50, 'quantity' => 1],
			];

			$total = array_sum(array_map(fn($o) => $o['price'] * $o['quantity'], $orders));

			// Load saved cards from database
			$savedCards = CartaDiCredito::where('id_utente', $_SESSION['LoggedUser']['id'])->get()->map(function($card) {
				return [
					'id' => $card->id,
					'last_four' => substr($card->numero_carta, -4),
					'brand' => $card->circuito_pagamento,
					'expiry' => $card->scadenza_mese . '/' . $card->scadenza_anno
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
								<button type="submit" name="selected_card" value="<?=$card['id'] ?>" class="card-button">Edit Card</button>
								<label for="card_<?= $card['id'] ?>">
									<!--TODO da modificare quando verrà aggiunta la colonna data nel database-->
									<?=htmlspecialchars($card['brand']) ?> •••• <?=$card['last_four'] ?> (Expires <?=$card['expiry'] ?>)
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
						<input type="text" name="card_name" required>
					</div>

					<div class="form-group">
						<label>Card Number</label>
						<input type="text" name="card_number" placeholder="1234 5678 9012 3456" required>
					</div>

					<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 10px;">
						<div class="form-group">
							<label>Expiry Date</label>
							<input type="text" name="expiry" placeholder="MM/YY" required>
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
	</body>
</html>