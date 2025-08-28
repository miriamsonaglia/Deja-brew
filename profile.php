<?php
	//TODO sostituire i ? con il modo per estrarre l'idUtente della sessione
	//controllare come si apre la sessione
	session_start();
	if (!isset($_SESSION['user_id'])) {
		header('Location: login.php');
		//mettere magari un popup di spiegazione
		exit;
	}
	$pdo = new PDO("mysql:host=localhost;dbname=ecommerce", "root", "password");


	// Dati profilo
	$stmt = $pdo->prepare("
		SELECT u.username, u.immagine_profilo, v.descrizione
		FROM utente u
		JOIN utenteVenditore v ON u.id = v.id_utente
		WHERE u.id = ?
	");
	$stmt->execute([$profile_id]);
	$profile = $stmt->fetch(PDO::FETCH_ASSOC);

	//controllare se utile
	if (!$user) {
		die("Utente non esistente.");
	}

	// Ordini acquistati
	$stmt = $pdo->prepare("
		SELECT o.*, p.nome AS product_name
		FROM ordine o
		JOIN prodotto p ON o.id_prodotto = p.id
		WHERE o.id_utente = ?
		ORDER BY o.id DESC
	");
	$stmt->execute([$profile_id]);
	$orders_acq = $stmt->fetchAll(PDO::FETCH_ASSOC);

	// Ordini venduti
	$stmt = $pdo->prepare("
		SELECT o.*, p.nome AS product_name
		FROM ordine o
		JOIN prodotto p ON o.id_prodotto = p.id
		JOIN utenteVenditore v ON p.id_venditore = v.id
		WHERE v.id_utente = ?
		ORDER BY o.id DESC
	");
	$stmt->execute([$profile_id]);
	$orders_vend = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="UTF-8">
	<title>Profilo di <?= htmlspecialchars($user['display_name']) ?></title>
	<style>
		body { font-family: sans-serif; margin: 30px auto; max-width: 800px; }
		img.profile { width: 120px; height: 120px; object-fit: cover; border-radius: 50%; }
		.tabs { margin-top: 30px; }
		.tab-button { display: inline-block; padding: 10px 20px; cursor: pointer; background: #eee; border: 1px solid #ccc; border-bottom: none; margin-right: 4px; }
		.tab-button.active { background: #fff; font-weight: bold; }
		.tab-content { border: 1px solid #ccc; padding: 20px; }
		table { width: 100%; border-collapse: collapse; margin-top: 10px; }
		th, td { padding: 8px; border: 1px solid #ccc; text-align: left; }
		.profile-header {
			display: flex;
			align-items: center;
			gap: 20px;
		}

		.profile-pic {
			width: 120px;
			height: 120px;
			border-radius: 50%;
			object-fit: cover;
		}

		.profile-info h2 {
			margin: 0;
			font-size: 1.8rem;
		}

		.profile-info p {
			margin: 5px 0 0;
			color: #555;
		}
	</style>
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

	<h1>Profilo: <?= htmlspecialchars($user['display_name']) ?></h1>

    <div class="profile-header">
	<?php if (!empty($user['profile_image'])): ?>
		<img class="profile" src="<?= htmlspecialchars($user['profile_image']) ?>" alt="Immagine profilo" class="profile-pic">
	<?php endif; ?>
	
	<!-- Da mettere un if che cambia il tipo di elemento dipendentemente se chi accede è il venditore o un utente qualsiasi -->
    <div class="profile-info">
        <h2><?= htmlspecialchars($profile['username']) ?> alt="Username"</h2>
        <p><?= htmlspecialchars($profile['descrizione']) ?> alt"Descrizione"</p>
    </div>
</div>

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
