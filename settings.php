<?php

	//controllare come si apre la sessione
	session_start();
	if (!isset($_SESSION['user_id'])) {
		header('Location: login.php');
		//mettere magari un popup di spiegazione
		exit;
	}
	$pdo = new PDO("mysql:host=localhost;dbname=ecommerce", "root", "password");

	$userId = $_SESSION['user_id'];

	// Crea le impostazioni predefinite se non esistono
	$pdo->exec("INSERT IGNORE INTO impostazioniUtente (id_utente, tema, notifiche)
    						VALUES ($userId, 'chiaro', 1)
	");

	// Se il form viene inviato, aggiorna le impostazioni
	$stmt = $pdo->prepare(
		"UPDATE impostazioniUtente SET
        tema = ?,
		notifiche = ?
		WHERE id_utente = ?
");
$stmt->execute([
    $_POST['tema'],
    $_POST['notifiche'],
    $userId
]);

	// Carica le impostazioni attuali
	$stmt = $pdo->prepare("SELECT * FROM impostazioniUtente WHERE id_utente = ?");
	$stmt->execute([$userId]);
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="UTF-8">
	<title>Impostazioni Utente</title>
	<style>
		body { font-family: sans-serif; max-width: 800px; margin: 0 auto; }
		h2 { border-bottom: 1px solid #ccc; padding-bottom: 5px; margin-top: 30px; }
		form { margin-top: 20px; }
		label { display: block; margin-top: 10px; }
		select { width: 100%; max-width: 300px; padding: 6px; margin-top: 4px; }
		.success { color: green; font-weight: bold; }
	</style>
</head>
<body>

	<h1>Impostazioni</h1>
	<?php if (isset($_GET['saved'])): ?>
		<p class="success">Impostazioni salvate con successo!</p>
	<?php endif; ?>

	<form method="POST">

		<!-- Notifiche -->
		<h2>Notifiche</h2>

		<label>
			Notifiche Email
			<select name="email_notifications">
				<option value="on" <?= $settings['email_notifications'] === 'on' ? 'selected' : '' ?>>Attive</option>
				<option value="off" <?= $settings['email_notifications'] === 'off' ? 'selected' : '' ?>>Disattive</option>
			</select>
		</label>

		<label>
			Notifiche Push
			<select name="push_notifications">
				<option value="on" <?= $settings['push_notifications'] === 'on' ? 'selected' : '' ?>>Attive</option>
				<option value="off" <?= $settings['push_notifications'] === 'off' ? 'selected' : '' ?>>Disattive</option>
			</select>
		</label>

		<!-- Accessibilità -->
		<h2>Accessibilità</h2>

		<label>
			Modalità Scura
			<select name="dark_mode">
				<option value="on" <?= $settings['dark_mode'] === 'on' ? 'selected' : '' ?>>Attiva</option>
				<option value="off" <?= $settings['dark_mode'] === 'off' ? 'selected' : '' ?>>Disattiva</option>
			</select>
		</label>

		<label>
			Dimensione del Testo
			<select name="text_size">
				<option value="small" <?= $settings['text_size'] === 'small' ? 'selected' : '' ?>>Piccolo</option>
				<option value="medium" <?= $settings['text_size'] === 'medium' ? 'selected' : '' ?>>Medio</option>
				<option value="large" <?= $settings['text_size'] === 'large' ? 'selected' : '' ?>>Grande</option>
			</select>
		</label>

		<!-- Privacy -->
		<h2>Privacy</h2>

		<label>
			Tracciamento dei Cookie
			<select name="cookie_tracking">
				<option value="enabled" <?= $settings['cookie_tracking'] === 'enabled' ? 'selected' : '' ?>>Abilitato</option>
				<option value="disabled" <?= $settings['cookie_tracking'] === 'disabled' ? 'selected' : '' ?>>Disabilitato</option>
			</select>
		</label>

		<br><br>
		<button type="submit">Salva Impostazioni</button>
	</form>

</body>
</html>
