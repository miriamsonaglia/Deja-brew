<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Profilo - Deja-brew</title>
    

        <?php
            // PHP initialization code remains the same
            use App\Models\Prodotto;
            use App\Models\Lista;
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
            
            if(!isset($_SESSION['LoggedUser']['id'])){
                header("Location: login.php");
                exit;
            }
            // --- Recupero dati utente senza usare la relazione ---
			
			$datiUtente = Utente::where('id', $_SESSION['LoggedUser']['id'])->first();
			if ($datiUtente === null) {
				// Handle missing user data: redirect or show error
				echo '<div class="alert alert-danger">Errore: dati utente non trovati.</div>';
				exit;
			}
			
			if ($_SESSION['UserRole'] == Role::VENDOR->value){
				$descSeller = UtenteVenditore::where('id', $_SESSION['LoggedUser']['id'])->select('descrizione')->first();
			}

        ?>
		
    </head>
	<body>
		<?php require_once __DIR__ . '/navbar-selector.php'; ?>

		<h1>Profilo: <?= htmlspecialchars($_SESSION['LoggedUser']['username']) ?></h1>

		<div class="profile-header">
		
			<?php if ($datiUtente && $datiUtente->immagine_profilo != null): ?>
				<img src="<?= htmlspecialchars($datiUtente->immagine_profilo) ?>" alt="Immagine profilo" class="profile-pic">
			<?php else: ?>
				<img src="/images/profiles/Default_Profile_Image.jpg" alt="Immagine profilo di default" class="profile-pic">
			
			<?php endif; ?>

			<!-- Da mettere un if che cambia il tipo di elemento dipendentemente se chi accede è il venditore o un utente qualsiasi -->
			<div class="profile-info">
				<h2><?= htmlspecialchars($datiUtente->username) ?> </h2>
				

				<?php if ($_SESSION['UserRole'] == Role::VENDOR->value): ?>
					<?php if ($descSeller->descrizione != null): ?>
						<p><?= htmlspecialchars($descSeller->descrizione) ?> </p>
					<?php else: ?>
						<p>Nessuna descrizione.</p>
					<?php endif; ?>
					<form id="edit_description_form" action="actions/edit_description.php" method="post">	
						<div class="mb-3">
							<label for="descrizione" class="form-label">Modifica Descrizione:</label>
							<textarea class="form-control" id="descrizione" name="descrizione" rows="3" required><?= isset($descSeller->descrizione) ? '' : '' ?></textarea>
						</div>
						<button type="submit" class="btn btn-primary">Aggiorna Descrizione</button>
					</form>
					<?php endif; ?>

			</div>
		</div>

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
				
				<button type="submit" class="btn btn-warning" disabled>Reset Password</button>
			</div>
		</form>
		


		<script>

			document.getElementById('edit_description_form').reset();
			document.getElementById('reset_password_form').reset();
		

			document.getElementById('reset_password_form').addEventListener('input', function() {
				const newPassword = document.getElementById('new_password').value;
				const confirmPassword = document.getElementById('new_password_confirm').value;
				const submitButton = document.querySelector('#reset_password_form button[type="submit"]');

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
		</script>
	</body>
</html>
