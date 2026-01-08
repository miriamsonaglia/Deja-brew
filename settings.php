<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
	<title>Impostazioni Utente</title>

	<!--
	Per aggiungere delle nuove impostazioni aggiungere dei nuovi elementi
	copiati da quelli sopra, i passaggi sono:
	1. Aggiungere il campo nel database (tabella impostazioniUtente)
	2. Aggiungere il campo nell'array $fillable e $casts in Models/ImpostazioniUtente.php
	3. Aggiungere la logica di validazione e salvataggio in actions/update_settings.php
	4. Aggiungere il campo a firstOrCreate qui di seguito
	5. Aggiungere il campo nel form HTML qua sotto
	-->

	<?php
		require_once __DIR__ . '/bootstrap.php';
		require_once __DIR__ . '/Models/Utente.php';
		require_once __DIR__ . '/Models/ImpostazioniUtente.php';

		use App\Models\Utente;
		use App\Models\ImpostazioniUtente;

		session_start();

		// --- Controllo utente loggato ---
		$datiUtente = Utente::where('id', $_SESSION['LoggedUser']['id'])->first();
		if ($datiUtente === null) {
			// Handle missing user data: redirect or show error
			echo '<div class="alert alert-danger">Errore: dati utente non trovati.</div>';
			exit;
		}

		// Crea le impostazioni predefinite se non esistono
		ImpostazioniUtente::firstOrCreate(
			['id_utente' => $datiUtente->id],
			[                           
				'tema' => 'chiaro',
				'notifiche' => 1,
				//'notifiche_mail' => 1,
				//'notifiche_push' => 1
			]
		);

		// Carica le impostazioni attuali
		$impostazioni = ImpostazioniUtente::where('id_utente', $datiUtente->id)->first();
		$settings = [
			'tema' => $impostazioni->tema,
			'notifiche' => $impostazioni->notifiche ? 'on' : 'off'
			//'notifiche_mail' => $impostazioni->notifiche_mail ? 'on' : 'off',
			//'notifiche_push' => $impostazioni->notifiche_push ? 'on' : 'off',
			//'cookie_tracking' => $impostazioni->cookie_tracking ? 'enabled' : 'disabled'
		];

	?>
</head>
<body>
	<?php require_once __DIR__ . '/navbar-selector.php'; ?>
	<h1>Impostazioni</h1>
	
	<?php
    if (isset($_SESSION['errors'])) {
        foreach ($_SESSION['errors'] as $error) {
            echo "<div class='alert alert-danger'>$error</div>";
        }
        unset($_SESSION['errors']);
    }
    ?>

    <?php if (isset($_GET['saved'])): ?>
        <p class="success">Impostazioni salvate con successo!</p>
    <?php endif; ?>

	<form action="actions/update_settings.php" method="POST">

		<!-- Notifiche -->
		<h2>Notifiche</h2>



		<label>
			Notifiche Generali
			<select name="notifiche">
				<option value="on" <?= $settings['notifiche'] === 'on' ? 'selected' : '' ?>>Attive</option>
				<option value="off" <?= $settings['notifiche'] === 'off' ? 'selected' : '' ?>>Disattive</option>
			</select>
		</label>
		<!--
		<label>
			Notifiche Email
			<select name="notifiche_mail">
				<option value="on" <?= $settings['notifiche_mail'] === 'on' ? 'selected' : '' ?>>Attive</option>
				<option value="off" <?= $settings['notifiche_mail'] === 'off' ? 'selected' : '' ?>>Disattive</option>
			</select>
		</label>

		<label>
			Notifiche Push
			<select name="notifiche_push">
				<option value="on" <?= $settings['notifiche_push'] === 'on' ? 'selected' : '' ?>>Attive</option>
				<option value="off" <?= $settings['notifiche_push'] === 'off' ? 'selected' : '' ?>>Disattive</option>
			</select>
		</label>
	-->
		<!-- Accessibilità -->
		<h2>Accessibilità</h2>

		<label>
			Modalità Scura
			<select name="tema">
				<option value="chiaro" <?= $settings['tema'] === 'chiaro' ? 'selected' : '' ?>>Chiaro</option>
				<option value="scuro" <?= $settings['tema'] === 'scuro' ? 'selected' : '' ?>>Scuro</option>
			</select>
		</label>

		<!-- Privacy -->
		<h2>Privacy</h2>

		<!--
		<label>
			Tracciamento dei Cookie
			<select name="cookie_tracking">
				<option value="enabled" <?= $settings['cookie_tracking'] === 'enabled' ? 'selected' : '' ?>>Abilitato</option>
				<option value="disabled" <?= $settings['cookie_tracking'] === 'disabled' ? 'selected' : '' ?>>Disabilitato</option>
			</select>
		</label>
		-->

		<br><br>
		<button type="submit">Salva Impostazioni</button>
	</form>

	<script>
		
	</script>

</body>
</html>
