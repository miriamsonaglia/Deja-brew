<!DOCTYPE html>
<html lang="it">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>Profilo - Deja-brew</title>
		<link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
		<link rel="stylesheet" href="./dist/bootstrap5/icons/bootstrap-icons.css">
		<link rel="stylesheet" href="./dist/custom/css/new-style.css">

		<?php
			require_once __DIR__ . '/bootstrap.php';
			require_once __DIR__ . '/Models/Utente.php';
			require_once __DIR__ . '/Models/UtenteCompratore.php';
			require_once __DIR__ . '/Models/UtenteVenditore.php';
			require_once __DIR__ . '/role.php';
			use App\Models\Utente;
			use App\Models\UtenteCompratore;
			use App\Models\UtenteVenditore;

			session_start();
			if (!isset($_SESSION['LoggedUser']['id'])) {
				header('Location: ./login.php');
				exit;
			}

			$utente = Utente::where('id', $_SESSION['LoggedUser']['id'])->first();
			$isBuyer = isset($_SESSION['UserRole']) && $_SESSION['UserRole'] === Role::BUYER->value;
			$isVendor = isset($_SESSION['UserRole']) && $_SESSION['UserRole'] === Role::VENDOR->value;

			$venditore = null;
			if ($isVendor) {
				$venditore = UtenteVenditore::where('id_utente', $utente->id)->first();
			}

			$avatar = !empty($utente->immagine_profilo)
				? $utente->immagine_profilo
				: './images/profiles/Default_Profile_Image.jpg';
		?>
	</head>
	<body>
		<?php require_once __DIR__ . '/navbar-selector.php'; ?>

		<div class="container py-4">
			<div class="row g-4 align-items-center">
				<div class="col-12 col-md-4 text-center">
					<img src="<?= htmlspecialchars($avatar) ?>" alt="Immagine profilo" class="img-fluid rounded-circle shadow" style="max-width: 220px;">
				</div>
				<div class="col-12 col-md-8">
					<div class="card shadow-sm">
						<div class="card-body">
							<div class="d-flex align-items-center justify-content-between">
								<h2 class="h4 mb-0"><?= htmlspecialchars($utente->username) ?></h2>
								<?php if ($isBuyer): ?>
									<span class="badge bg-primary">Acquirente</span>
								<?php elseif ($isVendor): ?>
									<span class="badge bg-warning text-dark">Venditore</span>
								<?php endif; ?>
							</div>
							<p class="text-muted mb-1"><?= htmlspecialchars($utente->nome ?? '') ?> <?= htmlspecialchars($utente->cognome ?? '') ?></p>
							<p class="mb-3"><i class="bi bi-envelope"></i> <?= htmlspecialchars($utente->email) ?></p>

							<?php if ($isVendor): ?>
								<div class="mt-3">
									<h6 class="text-uppercase text-muted">Descrizione Venditore</h6>
									<p class="mb-0"><?= htmlspecialchars($venditore->descrizione ?? 'Nessuna descrizione.') ?></p>
								</div>
							<?php endif; ?>

							<div class="mt-4 d-flex flex-wrap gap-2">
								<a href="./settings.php" class="btn btn-outline-secondary">
									<i class="bi bi-gear"></i> Impostazioni
								</a>
								<?php if ($isBuyer): ?>
									<a href="./orders-buyer.php" class="btn btn-primary">
										<i class="bi bi-bag"></i> I miei ordini
									</a>
									<a href="./wishlist.php" class="btn btn-outline-primary">
										<i class="bi bi-heart"></i> Wishlist
									</a>
									<a href="./cards-and-payment.php" class="btn btn-outline-dark">
										<i class="bi bi-credit-card"></i> Carte e Pagamenti
									</a>
								<?php elseif ($isVendor): ?>
									<a href="./orders-seller.php" class="btn btn-warning">
										<i class="bi bi-receipt"></i> Ordini Venduti
									</a>
									<a href="./add-product.php" class="btn btn-outline-warning">
										<i class="bi bi-plus-circle"></i> Aggiungi Prodotto
									</a>
								<?php endif; ?>
							</div>
						</div>
					</div>
				</div>
			</div>

			<div class="mt-4">
				<a href="./home.php" class="btn btn-link"><i class="bi bi-house"></i> Torna alla Home</a>
			</div>
		</div>

		<script src="./dist/bootstrap5/js/bootstrap.min.js"></script>
	</body>
</html>
