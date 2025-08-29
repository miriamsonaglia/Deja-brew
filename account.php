<?php

use App\Models\Utente;
use App\Models\UtenteVenditore;
//TODO far funzionare gli elementi, aggiungere a prodotti con js gli elementi a scorrimento
session_start(); //controllare dov'è inizializzata la sessione e come richiamarne i parametri
if (!isset($_SESSION['LoggedUser'])&&$_SESSION['UserRole']===Role::BUYER->value) {
	header('Location: login.php');
	die("Devi essere Compratore per visualizzare il profilo di un venditore.");
}

	// Carica i dati dell'utente
$user = Utente::select('display_name', 'description', 'immagine_profilo')
				->where('id', $_SESSION['user_id'])
				->first();

// Aggiorna i dati se viene inviato il form
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $username    = $_POST['username'] ?? '';
    $description = $_POST['descrizione'] ?? '';
	$profileImage = $user->immagine_profilo;

	if (!empty($_FILES['immagine_profilo']['tmp_name'])) {
		$uploadDir = __DIR__ . '/images/profiles/'; // directory fisica
		$fileName = basename($_FILES['immagine_profilo']['name']);
		$targetFile = $uploadDir . uniqid() . "_" . $fileName;

		if (move_uploaded_file($_FILES['immagine_profilo']['tmp_name'], $targetFile)) {
			// Salvo solo il percorso relativo (così è più facile da usare nell’HTML)
			$profileImage = 'images/profiles/' . basename($targetFile);
		}
	}

    // Aggiorna tabella utente
    $utente = Utente::find($_SESSION['user_id']);
    $utente->username = $username;
    $utente->immagine_profilo = $profileImage; // $profileImage gestito come nell’upload
    $utente->save();

    // Aggiorna tabella utenteVenditore
    UtenteVenditore::where('id_utente', $_SESSION['user_id'])
        ->update(['descrizione' => $description]);

    header("Location: account.php?updated=1");
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="UTF-8">
	<title>Il Mio Account</title>
<!--TODO da mettere in file style -->
<style>
	.tab-button {
		cursor: pointer;
		padding: 5px 10px;
		display: inline-block;
		border: 1px solid #ccc;
		margin-right: 5px;
	}

	.tab-button.active {
		background-color: #eee;
		font-weight: bold;
	}

	.tab-content {
		border: 1px solid #ccc;
		padding: 10px;
		margin-top: 5px;
	}
</style>
</head>
<body>
	<h1>Il Mio Account</h1>
	<?php if (isset($_GET['updated'])): ?>
		<p style="color: green;">Profilo aggiornato con successo!</p>
	<?php endif; ?>
    
<form action="account.php" method="POST" enctype="multipart/form-data">
    <!-- Immagine profilo -->
    <div>
        <label for="immagine_profilo">Immagine profilo</label><br>
        <input type="file" id="immagine_profilo" name="immagine_profilo" accept="image/*">
        <?php if (!empty($user['immagine_profilo'])): ?>
            <br>
            <img src="<?= htmlspecialchars($user['immagine_profilo']) ?>" alt="Profilo" style="width:100px;height:100px;border-radius:50%;object-fit:cover;">
        <?php endif; ?>
    </div>

    <!-- Username -->
    <div>
        <label for="username">Username</label><br>
        <input type="text" id="username" name="username" value="<?= htmlspecialchars($user['username'] ?? '') ?>" required>
    </div>

    <!-- Descrizione venditore -->
    <div>
        <label for="descrizione">Descrizione</label><br>
        <textarea id="descrizione" name="descrizione" rows="4" cols="40"><?= htmlspecialchars($venditore['descrizione'] ?? '') ?></textarea>
    </div>

    <br>
    <button type="submit" name="update_profile">Aggiorna Profilo</button>
</form>

<div class="tabs">
    <div>
        <span class="tab-button active" data-tab="acquistati">Ordini Acquistati</span>
        <span class="tab-button" data-tab="prodotti">Prodotti</span>
    </div>

    <!-- Tab Acquistati -->
    <div class="tab-content" data-tab="acquistati">
        <h3>Ordini Acquistati</h3>
        <?php if (empty($orders_acq)): ?>
            <p>Nessun ordine acquistato.</p>
        <?php else: ?>
            <table>
                <tr><th>Data</th><th>Prodotto</th><th>Quantità</th><th>Totale</th></tr>
                <?php foreach ($orders_acq as $o): ?>
                    <tr>
                        <td><?= $o['order_date'] ?></td>
                        <td><?= htmlspecialchars($o['product_name']) ?></td>
                        <td><?= $o['quantity'] ?></td>
                        <td>€ <?= number_format($o['price'] * $o['quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>

    <!-- Tab Prodotti -->
    <div class="tab-content" data-tab="prodotti" style="display: none;">
        <h3>Prodotti</h3>
        <?php if (empty($orders_vend)): ?>
            <p>Nessun ordine venduto.</p>
        <?php else: ?>
            <table>
                <tr><th>Data</th><th>Prodotto</th><th>Quantità</th><th>Totale</th></tr>
                <?php foreach ($orders_vend as $o): ?>
                    <tr>
                        <td><?= $o['order_date'] ?></td>
                        <td><?= htmlspecialchars($o['product_name']) ?></td>
                        <td><?= $o['quantity'] ?></td>
                        <td>€ <?= number_format($o['price'] * $o['quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </div>
</div>

</body>
<script>
	function switchTab(tabName) {
		// Aggiorna lo stato dei bottoni
		document.querySelectorAll('.tab-button').forEach(btn => {
			btn.classList.toggle('active', btn.dataset.tab === tabName);
		});

		// Mostra/nasconde i contenuti
		document.querySelectorAll('.tab-content').forEach(content => {
			content.style.display = content.dataset.tab === tabName ? 'block' : 'none';
		});
	}

	// Collega tutti i bottoni
	document.querySelectorAll('.tab-button').forEach(btn => {
		btn.addEventListener('click', () => switchTab(btn.dataset.tab));
	});

	// Mostra tab di default all'avvio
	window.addEventListener('DOMContentLoaded', () => switchTab('acquistati'));
</script>
</html>
