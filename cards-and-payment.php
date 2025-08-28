<?php

	//TODO sostituire i ? con i campi di sessione
	session_start();
	if (!isset($_SESSION['user_id'])) {
		header("Location: login.php");
		exit;
	}

	$userId = $_SESSION['user_id'];
	$pdo = new PDO("mysql:host=localhost;dbname=ecommerce", "root", "password");

	// Rimuovi carta
	$stmt = $pdo->prepare("DELETE FROM cartaDiCredito WHERE id = ? AND id_utente = ?");
	$stmt->execute([$_GET['remove'], $userId]);

	// Aggiungi carta
	$stmt = $pdo->prepare("
		INSERT INTO cartaDiCredito (id_utente, circuito_pagamento, codice_carta, cvv_carta, scadenza)
		VALUES (?, ?, ?, ?, ?)
	");
	$stmt->execute([
		$userId,
		$_POST['brand'],
		$_POST['card_number'],
		$_POST['cvv'],
		$_POST['expiry']
	]);

	// Recupera carte
	$cards = $pdo->prepare("SELECT * FROM cartaDiCredito WHERE id_utente = ?");
	$cards->execute([$userId]);
	$cards = $cards->fetchAll(PDO::FETCH_ASSOC);

	// Recupera pagamenti (fatture collegate agli ordini)
	$payments = $pdo->prepare("
		SELECT f.*, o.id AS ordine_id, p.nome AS product_name
		FROM fattura f
		JOIN ordine o ON f.id_ordine = o.id
		JOIN prodotto p ON o.id_prodotto = p.id
		WHERE f.id_utente = ?
	");
	$payments->execute([$userId]);
	$payments = $payments->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="UTF-8">
	<title>Pagamento e Fatturazione</title>
	<style>
		body { font-family: sans-serif; max-width: 800px; margin: 20px auto; }
		h2 { border-bottom: 1px solid #ccc; padding-bottom: 5px; }
		table { width: 100%; border-collapse: collapse; margin-top: 15px; }
		th, td { border: 1px solid #ccc; padding: 8px; text-align: left; }
		form { margin-top: 20px; }
		input, select { padding: 6px; margin: 5px 0; width: 100%; max-width: 300px; }
		.danger { color: red; }
	</style>
</head>
<body>

	<h1>Gestione Pagamenti e Fatturazione</h1>

	<!-- METODI DI PAGAMENTO -->
	<h2>Metodi di Pagamento Salvati</h2>
	<?php if (empty($cards)): ?>
		<p>Nessuna carta salvata.</p>
	<?php else: ?>
		<table>
			<tr><th>Intestatario</th><th>Ultime 4 cifre</th><th>Brand</th><th>Scadenza</th><th></th></tr>
			<?php foreach ($cards as $card): ?>
				<tr>
					<td><?= htmlspecialchars($card['card_holder']) ?></td>
					<td>**** **** **** <?= $card['last4'] ?></td>
					<td><?= htmlspecialchars($card['brand']) ?></td>
					<td><?= htmlspecialchars($card['expiry']) ?></td>
					<td><a class="danger" href="?remove=<?= $card['id'] ?>" onclick="return confirm('Rimuovere questa carta?')">Rimuovi</a></td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>

	<!-- AGGIUNTA CARTA -->
	<h2>Aggiungi Nuovo Metodo di Pagamento</h2>
	<form method="POST">
		<label>Intestatario Carta: <input type="text" name="card_holder" required></label><br>
		<label>Numero Carta: <input type="text" name="card_number" maxlength="16" required></label><br>
		<label>Brand: 
			<select name="brand">
				<option value="Visa">Visa</option>
				<option value="MasterCard">MasterCard</option>
				<option value="Amex">American Express</option>
			</select>
		</label><br>
		<label>Scadenza: <input type="month" name="expiry" required></label><br>
		<button type="submit">Salva Carta</button>
	</form>

	<!-- STORICO PAGAMENTI -->
	<h2>Storico Pagamenti</h2>
	<?php if (empty($payments)): ?>
		<p>Nessun pagamento registrato.</p>
	<?php else: ?>
		<table>
			<tr><th>Data</th><th>Ordine</th><th>Importo</th><th>Fattura</th></tr>
			<?php foreach ($payments as $p): ?>
				<tr>
					<td><?= $p['paid_at'] ?></td>
					<td><?= htmlspecialchars($p['product_name'] ?? 'Ordine #' . $p['order_id']) ?></td>
					<td>€ <?= number_format($p['amount'], 2) ?></td>
					<td>
						<?php if ($p['invoice_file']): ?>
							<a href="<?= htmlspecialchars($p['invoice_file']) ?>" target="_blank">Scarica</a>
						<?php else: ?>
							-
						<?php endif; ?>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>

</body>
</html>
