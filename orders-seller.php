<?php
	//TODO sostituire i ? con i campi di sessione
	session_start();
	if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'seller') {
		header("Location: login.php");
		exit;
	}
	$pdo = new PDO("mysql:host=localhost;dbname=ecommerce", "root", "password");

	$stmt = $pdo->prepare("
		SELECT o.*, p.nome AS product_name, u.nome AS buyer_name
		FROM ordine o
		JOIN prodotto p ON o.id_prodotto = p.id
		JOIN utente u ON o.id_utente = u.id
		JOIN utenteVenditore v ON p.id_venditore = v.id
		WHERE v.id_utente = ?
		ORDER BY o.id DESC
	");
	$stmt->execute([$_SESSION['user_id']]);
	$orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="it">
<head>
	<meta charset="UTF-8">
	<title>Ordini Ricevuti</title>
</head>
<body>
	<h1>Ordini Ricevuti</h1>
	<?php if (empty($orders)): ?>
		<p>Non hai ancora ricevuto ordini.</p>
	<?php else: ?>
		<table border="1" cellpadding="8">
			<tr>
				<th>Acquirente</th>
				<th>Prodotto</th>
				<th>Quantità</th>
				<th>Status</th>
				<th>Prezzo</th>
				<th>Fattura</th>
			</tr>
			<?php foreach ($orders as $order): ?>
				<tr>
					
					<td><?= $order['id_utente'] ?></td>
					<!--link prodotto e nome -->
					<td><?= htmlspecialchars($order['product_name']) ?></td>
					<!--probabilmente semplice divisione o si aggiunge ad ordine -->
					<td><?= $order['quantity'] ?></td>
					<td><?= htmlspecialchars($order['status']) ?></td>
					<td>€ <?= number_format($order['price'] * $order['quantity'], 2) ?></td>
					<td>
						<!--link al file della fattura -->
						<a href="generate_invoice.php?order_id=<?= $order['id'] ?>" target="_blank">Scarica PDF</a>
					</td>
				</tr>
			<?php endforeach; ?>
		</table>
	<?php endif; ?>
</body>
</html>
