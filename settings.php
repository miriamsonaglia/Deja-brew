<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Models/Utente.php';
require_once __DIR__ . '/Models/ImpostazioniUtente.php';

use App\Models\Utente;
use App\Models\ImpostazioniUtente;

session_start();

// --- Controllo utente loggato ---
if (!isset($_SESSION['LoggedUser'])) {
	die("Devi effettuare il login per accedere al checkout.");
}

$userId = $_SESSION['user_id'];

// Crea le impostazioni predefinite se non esistono
ImpostazioniUtente::firstOrCreate(
    ['id_utente' => $userId], // Condizione di ricerca
    [                           
        'tema' => 'chiaro',
        'notifiche_mail' => 1,
		'notifiche_push' => 1
    ]
);

// Carica le impostazioni attuali
$impostazioni = ImpostazioniUtente::where('id_utente', $userId)->first();

// Se il form viene inviato, aggiorna le impostazioni
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
	ImpostazioniUtente::where('id_utente', $userId)->update([
		//Con due opzioni posso usare comodamente un operatore binario
        'tema' => $$_POST['tema'] === 'on' ? 1 : 0,
        'notifiche_mail' => $$_POST['notifiche_mail'] === 'on' ? 1 : 0,
        'notifiche_push' => $$_POST['notifiche_push'] === 'on' ? 1 : 0,
    ]);
}

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

		<!-- Accessibilità -->
		<h2>Accessibilità</h2>

		<label>
			Modalità Scura
			<select name="tema">
				<option value="on" <?= $settings['tema'] === 'on' ? 'selected' : '' ?>>Attiva</option>
				<option value="off" <?= $settings['tema'] === 'off' ? 'selected' : '' ?>>Disattiva</option>
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
