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

	
		<div class="tabs">
			<div>
				<?php if ($_SESSION['UserRole'] == Role::BUYER->value): ?>
					<span id="acquistati-btn" class="tab-button" onclick="switchTab('acquistati')">Ordini Acquistati</span>
				<?php else: ?>
					<span id="venduti-btn" class="tab-button" onclick="switchTab('venduti')">Ordini Venduti</span>
				<?php endif; ?>
			</div>

			<?php if ($_SESSION['UserRole'] == Role::BUYER->value): ?>
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
			<?php endif; ?>
			
			<?php if ($_SESSION['UserRole'] == Role::VENDOR ->value): ?>
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
			<?php endif; ?>
		</div>
	

</body>
</html>
