<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Profilo - Deja-brew</title>
        <!-- Bootstrap 5 CSS -->
        <link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
        <link rel="stylesheet" href="./dist/bootstrap5/icons/bootstrap-icons.css">
        <link rel="stylesheet" href="./dist/custom/css/new-style.css">

        <?php
            // PHP initialization code remains the same
            use App\Models\Prodotto;
            use App\Models\Lista;
			use App\Models\Utente;
			use App\Models\UtenteCompratore;
			use App\Models\UtenteVenditore;
			use App\reusables\navbars;
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
			
			if ($_SESSION['UserRole'] == Role::VENDOR ->value){
				$descSeller = UtenteVenditore::where('id', $_SESSION['LoggedUser']['id'])->select('descrizione')->first();
			}

        ?>
		
    </head>
	<body>
		<?php require_once __DIR__ . '/navbar-selector.php'; ?>

		<h1>Profilo: <?= htmlspecialchars($_SESSION['LoggedUser']['username']) ?></h1>

		<div class="profile-header">
		
			<?php if ($datiUtente->immagine_profilo != null): ?>
				<img src="<?= htmlspecialchars($datiUtente->immagine_profilo) ?> alt="Immagine profilo" class="profile-pic">
			<?php else: ?>
				<img src="/images/profiles/Default_Profile_Image.jpg" alt="Immagine profilo di default" class="profile-pic">
			
			<?php endif; ?>

			<!-- Da mettere un if che cambia il tipo di elemento dipendentemente se chi accede è il venditore o un utente qualsiasi -->
			<div class="profile-info">
				<h2><?= htmlspecialchars($datiUtente->username) ?> </h2>
				

				<?php if ($_SESSION['UserRole'] == Role::VENDOR ->value): ?>
					<?php if ($descSeller->descrizione != null): ?>
						<p><?= htmlspecialchars($descSeller->descrizione) ?> </p>
					<?php else: ?>
						<p>Nessuna descrizione.</p>
					<?php endif; ?>
				<?php endif; ?>
			</div>
		</div>

		<h3>Reset Password</h3>
		<form id="reset_password_form" action="reset_password.php" method="post">
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

			document.getElementById('reset_password_form').addEventListener('submit', function(e) {
				e.preventDefault();
				const carta = document.getElementById('carta').value;
				const cvv = document.getElementById('cvv').value;

				const esito = document.getElementById('esitoPagamento') || document.createElement('div');
				esito.id = 'esitoPagamento';
				esito.className = 'alert mt-4';
				if(carta && cvv) {
					esito.classList.add('alert-success');
					esito.textContent = 'Pagamento effettuato con successo!';
				} else {
					esito.classList.add('alert-danger');
					esito.textContent = 'Errore: compilare tutti i campi.';
				}
				
				this.appendChild(esito);
			});

			document.getElementById('reset_password_form').addEventListener('input', function() {
				const newPassword = document.getElementById('new_password').value;
				const confirmPassword = document.getElementById('new_password_confirm').value;
				const submitButton = document.querySelector('#reset_password_form button[type="submit"]');

				const valido = document.getElementById('passwordCorrette') || document.createElement('div');
				valido.id = 'passwordCorrette';
				//valido.className = 'alert mt-4';	

				if (newPassword === confirmPassword && newPassword.length > 4) {
					submitButton.disabled = false;
					valido.classList.remove('alert-danger');
					this.removeChild(valido);
				} else {
					submitButton.disabled = true;
					valido.classList.add('alert-danger');
					valido.textContent = 'Le password non corrispondono o la password scelta è troppo corta.';
					this.appendChild(valido);
				}

			});
		</script>
	</body>
</html>
