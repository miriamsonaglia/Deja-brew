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

			use App\Models\Utente;
			use App\Models\UtenteCompratore;
			use App\Models\UtenteVenditore;
            require_once __DIR__ . '/bootstrap.php';
            require_once __DIR__ . '/Models/UtenteVenditore.php';
            require_once __DIR__ . '/Models/UtenteCompratore.php';
            require_once __DIR__ . '/Models/Utente.php';
            require_once __DIR__ . '/Models/Prodotto.php';
            require_once __DIR__ . '/Models/Lista.php';
            require_once __DIR__ . '/utilities.php';
            require_once __DIR__ . '/role.php';

            session_start();
			$userRole = $_SESSION['UserRole'] ?? null;
			$utente = Utente::where('id', $_SESSION['LoggedUser']['id'])->first();
			if ($utente === null) {
				// Handle missing user data: redirect or show error
				echo '<div class="alert alert-danger">Errore: dati utente non trovati.</div>';
				exit;
			}

			$isBuyer = isset($_SESSION['UserRole']) && $_SESSION['UserRole'] === Role::BUYER->value;
			$isVendor = isset($_SESSION['UserRole']) && $_SESSION['UserRole'] === Role::VENDOR->value;

			$venditore = null;
			if ($isVendor) {
				$venditore = UtenteVenditore::where('id_utente', $utente->id)->first();
			}

			// Determine profile image path and if the path contains a file
			if(!empty($utente->immagine_profilo)){
				$avatar = 'uploads/profile_images/' . $utente->immagine_profilo;
				if(!file_exists($avatar)){
					$avatar = './images/profiles/Default_Profile_Image.jpg';
				}
			} else {
				$avatar = './images/profiles/Default_Profile_Image.jpg';
			}

		?>
		<style>
			#drop-zone {
				border: 2px dashed #ccc;
				border-radius: 10px;
				padding: 20px;
				text-align: center;
				cursor: pointer;
			}
			#drop-zone:hover {
				background-color: #f0f0f0;
			}
			#preview {
				max-height: 65vh;
				width: auto;         /* maintain aspect ratio */
				display: block;      /* ensure block-level for consistent sizing */
				object-fit: contain; /* optional: fit inside box without stretching */
			}
		</style>
	</head>
	<body>
		<?php require_once __DIR__ . '/navbar-selector.php'; ?>

		<div class="container py-4">
			<div class="row g-4 align-items-center">
				<div class="col-12 col-md-4 text-center">
					<img src="<?= htmlspecialchars($avatar) ?>" alt="Immagine profilo" class="img-fluid w-100 rounded" style="max-width: 220px;">
					<button class="btn btn-secondary mt-3" data-bs-toggle="modal" data-bs-target="#modalUploadImg">Modifica Immagine Profilo</button>
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
						</div>
					</div>
				</div>
			</div>
			<!-- Da mettere un if che cambia il tipo di elemento dipendentemente se chi accede è il venditore o un utente qualsiasi -->
			<div class="profile-info">
				<h2 class="mt-5 mb-4">Impostazioni Profilo</h2>

				<?php if ($_SESSION['UserRole'] == Role::VENDOR->value): ?>
					<?php if ($venditore->descrizione != null): ?>
						<p><?= htmlspecialchars($venditore->descrizione) ?> </p>
					<?php else: ?>
						<p>Nessuna descrizione.</p>
					<?php endif; ?>
					<form id="edit_description_form" action="actions/edit_description.php" method="post">
						<div class="mb-3">
							<label for="descrizione" class="form-label">Modifica Descrizione:</label>
							<textarea class="form-control" id="descrizione" name="descrizione" rows="3" required></textarea>
						</div>
						<button type="submit" class="btn btn-primary">Aggiorna Descrizione</button>
					</form>
				<?php endif; ?>

				<hr class="my-5">

				<h3>Reset Password</h3>

				<?php
					// Messaggi generali (se presenti)
					if (isset($_SESSION['errors'])) {
						foreach ($_SESSION['errors'] as $error) {
							echo "<div class='alert alert-danger'>$error</div>";
						}
						unset($_SESSION['errors']);
					}
					if (isset($_SESSION['success'])) {
						echo "<div class='alert alert-success'>{$_SESSION['success']}</div>";
						unset($_SESSION['success']);
					}
				?>

				<?php
					// Messaggi specifici per reset password
					if (isset($_SESSION['errors_reset'])) {
						foreach ($_SESSION['errors_reset'] as $error) {
							echo "<div class='alert alert-danger'>$error</div>";
						}
						unset($_SESSION['errors_reset']);
					}
					if (isset($_SESSION['success_reset'])) {
						echo "<div class='alert alert-success'>{$_SESSION['success_reset']}</div>";
						unset($_SESSION['success_reset']);
					}
				?>

				<form id="reset_password_form" action="actions/reset_password.php" method="post">
					<div class="mb-3">
						<label for="current_password" class="form-label">Password Attuale</label>
						<input type="password" class="form-control" id="current_password" name="current_password" required>

						<label for="new_password" class="form-label">Nuova Password</label>
						<input type="password" class="form-control" id="new_password" name="new_password" required>

						<label for="new_password_confirm" class="form-label">Conferma nuova Password</label>
						<input type="password" class="form-control" id="new_password_confirm" name="new_password_confirm" required>

						<button type="submit" class="btn btn-warning" id="new_password_submit" disabled>Reset Password</button>
					</div>
				</form>
			</div>

		</div>

		<div class="modal fade" id="modalUploadImg" tabindex="-1" aria-labelledby="modalUploadImgLabel" aria-hidden="true">
			<div class="modal-dialog">
				<div class="modal-content">
				<form id="formUploadImg" action="actions/upload_image.php" method="POST" enctype="multipart/form-data">
				<div class="modal-header">
					<h5 class="modal-title" id="modalUploadImgLabel">Carica una nuova immagine profilo</h5>
					<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
					</div>
					<input type="hidden" id="modal-user_id" name="user_id">
					<div class="modal-body">


						<div id="drop-zone">
							Drop image here or click to upload
							<input type="file" name="image" accept="image/*" hidden>
						</div>
						<img id="preview" class="img-fluid mt-3 d-none" />



					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Annulla</button>
						<button type="submit" class="btn btn-success">Carica Immagine</button>
					</div>
				</form>
				</div>
			</div>
		</div>

		<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
		<script src="./dist//custom//js/cart-manager.js"></script>

		<script>
			formEdit = document.getElementById('edit_description_form')
			formReset = document.getElementById('reset_password_form')

			if(formEdit) document.getElementById('edit_description_form').reset();
			if(formReset) document.getElementById('reset_password_form').reset();


			document.getElementById('reset_password_form').addEventListener('input', function() {
				const newPassword = document.getElementById('new_password').value;
				const confirmPassword = document.getElementById('new_password_confirm').value;
				const submitButton = document.getElementById('new_password_submit');

				const valido = document.getElementById('passwordCorrette') || document.createElement('div');
				valido.id = 'passwordCorrette';

				if ((newPassword === confirmPassword) && (newPassword.length > 4)) {
					submitButton.disabled = false;
					valido.classList.remove('error');
					if (this.contains(valido)) {
						this.removeChild(valido);
					}

				} else{
					submitButton.disabled = true;
					valido.classList.add('error');
					valido.textContent = 'Le password non corrispondono o la password scelta è troppo corta.';
					if (!document.getElementById('passwordCorrette')) {
						this.appendChild(valido);
					}
					valido.textContent = 'Le password non corrispondono o la password scelta è troppo corta.';
					this.appendChild(valido);
				}

			});


			const dropZone = document.getElementById('drop-zone');
			const fileInput = dropZone.querySelector('input');

			dropZone.addEventListener('click', () => fileInput.click());

			dropZone.addEventListener('dragover', e => {
				e.preventDefault();
				dropZone.classList.add('dragover');
			});

			dropZone.addEventListener('dragleave', () => {
				dropZone.classList.remove('dragover');
			});

			dropZone.addEventListener('drop', e => {
				e.preventDefault();
				dropZone.classList.remove('dragover');
				fileInput.files = e.dataTransfer.files;
			});

			fileInput.addEventListener('change', () => {
				const file = fileInput.files[0];
				if (!file) return;

				const img = document.getElementById('preview');
				img.src = URL.createObjectURL(file);
				img.classList.remove('d-none');
			});

		<?php if(isset($userRole) && ($userRole === Role::BUYER->value)): ?>
			updateCartCount();
		<?php endif; ?>
		</script>
	</body>
</html>
