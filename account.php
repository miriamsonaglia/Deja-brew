<?php

//TODO ancora da fixare

	session_start(); //controllare dov'è inizializzata la sessione e come richiamarne i parametri
	if (!isset($_SESSION['LoggedUser'])&&$_SESSION['UserRole']===Role::VENDOR->value) {
		header('Location: login.php');
		die("Devi essere Venditore per accedere ad Account.");
	}
	// FIXME: UTILIZZIAMO ELOQUENT, NON OCCORRE PDO
	$pdo = new PDO("mysql:host=localhost;dbname=ecommerce", "root", "password");

	// Carica i dati dell'utente
	$stmt = $pdo->prepare("SELECT display_name, description, immagine_profilo FROM users WHERE id = ?");
	$stmt->execute([$_SESSION['user_id']]);
	$user = $stmt->fetch(PDO::FETCH_ASSOC);

	// Aggiorna i dati se viene inviato il form
	if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
		$username    = $_POST['username'] ?? '';
		$description = $_POST['descrizione'] ?? '';
		
		// Gestione immagine
		//TODO controllare dove mandare le immagini caricate
		$profileImage = $user['immagine_profilo']; // valore attuale
		if (!empty($_FILES['immagine_profilo']['tmp_name'])) {
			$uploadDir = 'uploads/';
			$fileName = basename($_FILES['immagine_profilo']['name']);
			$targetFile = $uploadDir . uniqid() . "_" . $fileName;
			move_uploaded_file($_FILES['immagine_profilo']['tmp_name'], $targetFile);
			$profileImage = $targetFile;
		}

		// Aggiorna tabella utente
		$stmt = $pdo->prepare("UPDATE utente SET username = ?, immagine_profilo = ? WHERE id = ?");
		$stmt->execute([$username, $profileImage, $_SESSION['user_id']]);

		// Aggiorna tabella utenteVenditore
		$stmt2 = $pdo->prepare("UPDATE utenteVenditore SET descrizione = ? WHERE id_utente = ?");
		$stmt2->execute([$description, $_SESSION['user_id']]);

		header("Location: account.php?updated=1");
		exit;
	}
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="UTF-8">
	<title>Il Mio Account</title>
	<script>
		function switchTab(tab) {
			document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
			document.querySelectorAll('.tab-content').forEach(div => div.style.display = 'none');
			document.getElementById(tab + '-btn').classList.add('active');
			document.getElementById(tab + '-content').style.display = 'block';
		}
		window.onload = () => switchTab('acquistati');
	</script>
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


<?php if ($isOwner): ?>
		<div class="tabs">
			<div>
				<span id="acquistati-btn" class="tab-button" onclick="switchTab('acquistati')">Ordini Acquistati</span>
				<span id="venduti-btn" class="tab-button" onclick="switchTab('venduti')">Ordini Venduti</span>
			</div>

			<div id="acquistati-content" class="tab-content">
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

			<div id="venduti-content" class="tab-content" style="display: none;">
				<h3>Ordini Venduti</h3>
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
	<?php endif; ?>

</body>
</html>
