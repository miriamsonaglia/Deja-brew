<?php
	require_once __DIR__ . '/bootstrap.php';
	require_once __DIR__ . '/Models/Ordine.php';
	require_once __DIR__ . '/Models/Prodotto.php';
	require_once __DIR__ . '/Models/Utente.php';
	require_once __DIR__ . '/role.php';
	use App\Models\Ordine;
	use App\Models\Prodotto;
	use App\Models\Utente;

	session_start();
	if (!isset($_SESSION['LoggedUser']['id']) || ($_SESSION['UserRole'] ?? Role::GUEST->value) !== Role::BUYER->value) {
		header('Location: ./login.php');
		exit;
	}

	$userId = $_SESSION['LoggedUser']['id'];
	$orders = Ordine::with('prodotto')
		->where('id_utente', $userId)
		->orderBy('id', 'desc')
		->get();
?>
<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>I Miei Ordini</title>
	<link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
	<link rel="stylesheet" href="./dist/bootstrap5/icons/bootstrap-icons.css">
	<link rel="stylesheet" href="./dist/custom/css/new-style.css">
	<style>
		.orders-table th, .orders-table td { vertical-align: middle; }
	</style>
 </head>
<body>
	<?php require_once __DIR__ . '/navbar-selector.php'; ?>
	<div class="container py-4">
		<h1 class="mb-3">I Miei Ordini</h1>
		<?php if ($orders->isEmpty()): ?>
			<div class="card shadow-sm text-center p-4">
				<div class="mb-2"><i class="bi bi-bag fs-1 text-muted"></i></div>
				<p class="mb-1">Non hai ancora effettuato ordini.</p>
				<a href="./home.php" class="btn btn-primary mt-2">Inizia a comprare</a>
			</div>
		<?php else: ?>
			<div class="card shadow-sm">
				<div class="card-body p-0">
					<table class="table table-striped mb-0 orders-table">
						<thead>
							<tr>
								<th>Prodotto</th>
								<th>Status</th>
								<th>Totale</th>
								<th>Fattura</th>
							</tr>
						</thead>
						<tbody>
						<?php foreach ($orders as $order): ?>
							<tr>
								<td>
									<?php if ($order->prodotto): ?>
										<a href="./product.php?id=<?= $order->prodotto->id ?>" class="text-decoration-none">
											<?= htmlspecialchars($order->prodotto->nome) ?>
										</a>
									<?php else: ?>
										Prodotto
									<?php endif; ?>
								</td>
								<td><?= htmlspecialchars($order->status) ?></td>
								<td><?= number_format((float)$order->prezzo_totale, 2) ?> €</td>
								<td>
									<a href="generate_invoice.php?order_id=<?= $order->id ?>" class="btn btn-sm btn-outline-secondary" target="_blank">
										<i class="bi bi-file-earmark-pdf"></i> Scarica PDF
									</a>
								</td>
							</tr>
						<?php endforeach; ?>
						</tbody>
					</table>
				</div>
			</div>
		<?php endif; ?>

	</div>

	<script src="./dist/bootstrap5/js/bootstrap.min.js"></script>
</body>
</html>
