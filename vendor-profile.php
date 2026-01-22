<!DOCTYPE html>
<html lang="it">
	<head>
		<meta charset="UTF-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="Profilo del venditore su Deja-brew. Visualizza informazioni, prodotti e ordini.">
		<title>Profilo Venditore - Deja-brew</title>
		<link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
		<link rel="stylesheet" href="./dist/bootstrap5/icons/bootstrap-icons.css">
		<link rel="stylesheet" href="./dist/custom/css/new-style.css">

		<?php
			use App\Models\UtenteVenditore;
			use App\Models\Utente;
			use App\Models\Ordine;
			require __DIR__ . '/bootstrap.php';
			require_once __DIR__ . '/role.php';

			session_start();

			if (!isset($_SESSION['LoggedUser']['id'])) {
				echo '<div class="alert alert-danger">Errore: devi effettuare il login.</div>';
				exit;
			}

			$utente = Utente::where('id', $_SESSION['LoggedUser']['id'])->first();
			if ($utente === null) {
				echo '<div class="alert alert-danger">Errore: dati utente non trovati.</div>';
				exit;
			}

			// Get the user ID from query string
			$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
			

			if ($id <= 0) {
				die("Invalid user ID");
			}

			$vendor = UtenteVenditore::where('id_utente', $id)->first();

			// Checks if the id is of a vendor
			if ($vendor === null) {
				die("Id not of a vendor user");
			}

			// Fetch the user
			$user = Utente::find($id);
			if (!$user) {
				die("User not found");
			}

			// Determine profile image path and if the path contains a file
			if (!empty($user->immagine_profilo)) {
				$avatar = 'uploads/profile_images/' . $user->immagine_profilo;
				if (!file_exists($avatar)) {
					$avatar = './images/profiles/Default_Profile_Image.jpg';
				}
			} else {
				$avatar = './images/profiles/Default_Profile_Image.jpg';
			}

			$vendorId = $vendor->id;
			$orders = Ordine::with('prodotto')
				->whereHas('prodotto', function($query) use ($vendorId) {
					$query->where('id_venditore', $vendorId);
				})
				->orderBy('id', 'desc')
				->get();

			$prodotti = $vendor->prodotti()->get();

			$priceRanges = [
				['min' => 0, 'max' => 50, 'label' => '<50€'],
				['min' => 51, 'max' => 100, 'label' => '<100€'],
				['min' => 101, 'max' => 200, 'label' => '<200€'],
				['min' => 201, 'max' => PHP_INT_MAX, 'label' => '>200€'],
			];

			// Function to map price to a range
			function mapPriceToRange($price, $ranges) {
				foreach ($ranges as $range) {
					if ($price >= $range['min'] && $price <= $range['max']) {
						return $range['label'];
					}
				}
				return "Unknown";
			}
		?>
	</head>
	<body>
		<!-- Skip Link per accessibilità -->
		<a href="#main-content" class="skip-link">Salta al contenuto principale</a>
		
		<?php require_once __DIR__ . '/navbar-selector.php'; ?>

		<main id="main-content" role="main" aria-label="Contenuto principale">
				<div class="container py-4">
				<!-- Sezione Profilo Venditore -->
				<section aria-labelledby="profile-heading" class="mb-4">
					<h1 id="profile-heading" class="visually-hidden">Profilo di <?= htmlspecialchars($user->username ?? 'Venditore') ?></h1>
					<div class="row g-4 align-items-center">
						<div class="col-12 col-md-4 text-center">
							<img src="<?= htmlspecialchars($avatar) ?>" 
								 alt="Immagine del profilo di <?= htmlspecialchars($user->username ?? 'Venditore') ?>" 
								 class="img-fluid w-100 rounded relative-30-percent"
								 role="img">
						</div>
						<div class="col-12 col-md-8">
								<div class="card shadow-sm">
							<div class="card-body">
								<div class="d-flex align-items-center justify-content-between">
									<h2 class="h4 mb-0" id="vendor-name"><?= htmlspecialchars($user->username ?? 'No Name') ?></h2>
									<span class="badge bg-warning text-dark" role="status" aria-label="Ruolo: Venditore">Venditore</span>
								</div>
								<p class="text-muted mb-1">
									<span class="visually-hidden">Nome completo: </span>
									<?= htmlspecialchars($user->nome ?? '') ?> <?= htmlspecialchars($user->cognome ?? '') ?>
								</p>
								<p class="mb-3">
									<i class="bi bi-envelope" aria-hidden="true"></i> 
									<span class="visually-hidden">Email: </span>
									<a href="mailto:<?= htmlspecialchars($user->email ?? '') ?>" aria-label="Invia email a <?= htmlspecialchars($user->username ?? 'venditore') ?>">
										<?= htmlspecialchars($user->email ?? '-') ?>
									</a>
								</p>

								<div class="mt-3">
									<h3 class="h6 text-uppercase text-muted">Descrizione Venditore</h3>
									<p class="mb-0"><?= htmlspecialchars($vendor->descrizione ?? 'Nessuna descrizione.') ?></p>
								</div>
							</div>
						</div>
					</div>
				</section>

				<!-- Navigazione a Tab -->
				<nav aria-label="Navigazione sezioni profilo venditore">
					<ul class="nav nav-tabs mb-3" id="vendorTabs" role="tablist">
						<li class="nav-item" role="presentation">
							<button class="nav-link active" 
									id="informazioni-tab" 
									data-bs-toggle="tab" 
									data-bs-target="#informazioni" 
									type="button" 
									role="tab" 
									aria-controls="informazioni" 
									aria-selected="true"
									aria-label="Visualizza informazioni del venditore">
								<i class="bi bi-info-circle" aria-hidden="true"></i> Informazioni
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" 
									id="venduti-tab" 
									data-bs-toggle="tab" 
									data-bs-target="#venduti" 
									type="button" 
									role="tab" 
									aria-controls="venduti" 
									aria-selected="false"
									aria-label="Visualizza ordini venduti">
								<i class="bi bi-cart-check" aria-hidden="true"></i> Ordini Venduti
							</button>
						</li>
						<li class="nav-item" role="presentation">
							<button class="nav-link" 
									id="prodotti-tab" 
									data-bs-toggle="tab" 
									data-bs-target="#prodotti" 
									type="button" 
									role="tab" 
									aria-controls="prodotti" 
									aria-selected="false"
									aria-label="Visualizza prodotti del venditore">
								<i class="bi bi-box-seam" aria-hidden="true"></i> Prodotti
							</button>
						</li>
					</ul>
				</nav>

				<!-- Contenuto Tab -->
				<div class="tab-content" id="vendorTabsContent">
					<!-- Informazioni Tab -->
					<div class="tab-pane fade show active" id="informazioni" role="tabpanel" aria-labelledby="informazioni-tab" tabindex="0">
						<div class="card shadow-sm">
							<div class="card-body">
								<h2 class="h5 card-title">Informazioni sul venditore</h2>
							<div class="row">
								<div class="col-md-6">
									<p><strong>Email:</strong> <?= htmlspecialchars($user->email ?? '-') ?></p>
								</div>
							</div>
							<div class="mt-3">
								<p><strong>Bio:</strong><br><?= htmlspecialchars($vendor->descrizione ?? 'Nessuna descrizione disponibile.') ?></p>
							</div>
						</div>
					</div>
				</div>

					<!-- Ordini Venduti Tab -->
					<div class="tab-pane fade" id="venduti" role="tabpanel" aria-labelledby="venduti-tab" tabindex="0">
						<div class="card shadow-sm">
							<div class="card-body">
								<h2 class="h5 card-title">Ordini Venduti</h2>
								<?php if ($orders->isEmpty()): ?>
									<p class="text-muted" role="status">Nessun ordine trovato.</p>
								<?php else: ?>
									<div class="table-responsive">
										<table class="table table-striped table-hover mb-0" aria-label="Tabella ordini venduti">
										<caption class="visually-hidden">Elenco degli ordini venduti dal venditore con <?= count($orders) ?> ordini totali</caption>
									<thead>
										<tr>
											<th scope="col">Acquirente</th>
											<th scope="col">Prodotto</th>
											<th scope="col">Status</th>
											<th scope="col">Totale</th>
											</tr>
										</thead>
												<tbody>
													<?php foreach ($orders as $index => $order): ?>
														<tr>
															<th scope="row"><?= htmlspecialchars($order->utente->username ?? ($order->utente->nome ?? 'Utente')) ?></th>
															<td>
																<?php if ($order->prodotto): ?>
																	<a href="./product.php?id=<?= $order->prodotto->id ?>" 
																	   class="text-decoration-none"
																	   aria-label="Visualizza dettagli prodotto <?= htmlspecialchars($order->prodotto->nome) ?>">
																		<?= htmlspecialchars($order->prodotto->nome) ?>
																	</a>
																<?php else: ?>
																	<span aria-label="Prodotto non disponibile">Prodotto</span>
																<?php endif; ?>
															</td>
															<td>
																<span class="badge bg-info" role="status" aria-label="Stato ordine: <?= htmlspecialchars($order->status) ?>">
																	<?= htmlspecialchars($order->status) ?>
																</span>
															</td>
															<td><span aria-label="Prezzo totale"><?= mapPriceToRange($order->prezzo_totale, $priceRanges) ?></span></td>
														</tr>
													<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							<?php endif; ?>
						</div>
					</div>
				</div>

					<!-- Prodotti Tab -->
					<div class="tab-pane fade" id="prodotti" role="tabpanel" aria-labelledby="prodotti-tab" tabindex="0">
						<div class="card shadow-sm">
							<div class="card-body">
								<h2 class="h5 card-title">Prodotti</h2>
								<?php if ($prodotti->isEmpty()): ?>
									<p class="text-muted" role="status">Nessun prodotto trovato.</p>
								<?php else: ?>
									<div class="table-responsive">
										<table class="table table-striped table-hover mb-0" aria-label="Tabella prodotti del venditore">
											<caption class="visually-hidden">Elenco dei prodotti venduti con <?= count($prodotti) ?> prodotti totali</caption>
										<thead>
											<tr>
											<th scope="col">Prodotto</th>
											<th scope="col">Tipo</th>
											<th scope="col">Intensità</th>
											<th scope="col">Prezzo</th>
											</tr>
										</thead>
											<tbody>
												<?php foreach ($prodotti as $prodotto): ?>
													<tr>
														<th scope="row">
															<a href="./product.php?id=<?= $prodotto->id ?>" 
															   class="text-decoration-none"
															   aria-label="Visualizza dettagli di <?= htmlspecialchars($prodotto->nome) ?>">
																<?= htmlspecialchars($prodotto->nome) ?>
															</a>
														</th>
														<td><?= htmlspecialchars($prodotto->tipo) ?></td>
														<td><span aria-label="Intensità <?= htmlspecialchars($prodotto->intensita) ?>"><?= htmlspecialchars($prodotto->intensita) ?></span></td>
														<td><span aria-label="Prezzo <?= number_format($prodotto->prezzo, 2) ?> euro"><?= number_format($prodotto->prezzo, 2) ?>€</span></td>
													</tr>
												<?php endforeach; ?>
										</tbody>
									</table>
								</div>
							<?php endif; ?>
						</div>
					</div>
					</div>
				</div>
			</div>
		</main>

		<script src="./dist/bootstrap5/js/bootstrap.bundle.min.js"></script>
		<script>
			// Gestione accessibilità tab con tastiera
			document.addEventListener('DOMContentLoaded', function() {
				const tabButtons = document.querySelectorAll('[role="tab"]');
				
				tabButtons.forEach(button => {
					button.addEventListener('shown.bs.tab', function(e) {
						// Sposta il focus sul pannello quando viene attivato un tab
						const targetId = e.target.getAttribute('data-bs-target');
						const targetPanel = document.querySelector(targetId);
						if (targetPanel) {
							targetPanel.focus();
						}
					});
				});
				
				// Annuncia il cambio di tab agli screen reader
				const tabList = document.querySelector('[role="tablist"]');
				if (tabList) {
					const liveRegion = document.createElement('div');
					liveRegion.setAttribute('role', 'status');
					liveRegion.setAttribute('aria-live', 'polite');
					liveRegion.classList.add('visually-hidden');
					document.body.appendChild(liveRegion);
					
					tabButtons.forEach(button => {
						button.addEventListener('shown.bs.tab', function(e) {
							liveRegion.textContent = 'Visualizzazione sezione: ' + e.target.textContent.trim();
						});
					});
				}
			});
		</script>
	</body>
</html>
