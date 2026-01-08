<?php

    require __DIR__ . '/bootstrap.php';

    use App\Models\UtenteVenditore;
    use App\Models\Utente;
    use App\Models\Ordine;

    session_start();
                
    $utente = Utente::where('id', $_SESSION['LoggedUser']['id'])->first();
    if ($utente == null) {
        // Handle missing user data: redirect or show error
        echo '<div class="alert alert-danger">Errore: dati utente non trovati.</div>';
        exit;
    }

    // Get the user ID from query string
    $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
    if ($id <= 0) {
        die("Invalid user ID");
    }

    $vendor = UtenteVenditore::where('id_utente', $id)->first();
    
    //Checks if the id is of a vendor
    if ($vendor == null) {
        die("Id not of a vendor user");
    }

    // Fetch the user
    $user = Utente::find($id);
    if (!$user) {
        die("User not found");
    }

    // Determine profile image path and if the path contains a file
    if(!empty($user->immagine_profilo)){
        $avatar = 'uploads/profile_images/' . $user->immagine_profilo;
        if(!file_exists($avatar)){
            $avatar = './images/profiles/Default_Profile_Image.jpg';
        }
    } else {
        $avatar = './images/profiles/Default_Profile_Image.jpg';
    }

    $vendorId = $vendor->id;
	$orders = Ordine::with('prodotto') // eager load product info
                    ->whereHas('prodotto', function($query) use ($vendorId) {
                        $query->where('id_venditore', $vendorId);
                    })
                        ->orderBy('id', 'desc')
                        ->get();

    $prodotti = $vendor->prodotti()->get();

    $priceRanges = [
        ['min' => 0, 'max' => 50, 'label' => '<50€'],
        ['min' => 51, 'max' => 100, 'label' => '<100€'],
        ['min' => 101, 'max' => 200, 'label' => '<200€'],
        ['min' => 201, 'max' => PHP_INT_MAX, 'label' => '>200€'],
    ];

    // Function to map price to a range
    function mapPriceToRange($price, $ranges) {
        foreach ($ranges as $range) {
            if ($price >= $range['min'] && $price <= $range['max']) {
                return $range['label'];
            }
        }
        return "Unknown";
    }


?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
	    <meta name="viewport" content="width=device-width, initial-scale=1">
        <title><?= htmlspecialchars($user->username ?? 'User Profile') ?> - DejaBrew</title>
        <link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
        <link rel="stylesheet" href="./dist/bootstrap5/icons/bootstrap-icons.css">
        <link rel="stylesheet" href="./dist/custom/css/new-style.css">
        <style>
            .tabs { margin-top: 30px; }
            .tab-button { display: inline-block; padding: 10px 20px; cursor: pointer; background: #eee; border: 1px solid #ccc; border-bottom: none; margin-right: 4px; }
            .tab-button.active { background: #fff; font-weight: bold; }
            .tab-content { border: 1px solid #ccc; padding: 20px; }
            table { width: 100%; border-collapse: collapse; margin-top: 10px; }
            th, td { padding: 8px; border: 1px solid #ccc; text-align: left; }
        </style>
        
    </head>
    <body>
        <?php require_once __DIR__ . '/navbar-selector.php'; ?>
        <div class="profile text-center mb-4">
            <h2><?= htmlspecialchars($user->username ?? 'No Name') ?></h2>
            <img src="<?= htmlspecialchars($avatar) ?>" alt="Immagine profilo" class="img-fluid rounded-circle shadow" style="max-height: 500px;">
        </div>

        <div class="tabs">
			<div>
				<span id="informazioni-btn" class="tab-button" onclick="switchTab('informazioni')">Informazioni</span>
				<span id="venduti-btn" class="tab-button" onclick="switchTab('venduti')">Ordini Venduti</span>
                <span id="prodotti-btn" class="tab-button" onclick="switchTab('prodotti')">Prodotti</span>
			</div>

			<div id="informazioni-content" class="tab-content">
				<h3>Informazioni sul venditore</h3>
				<p><strong>Email:</strong> <?= htmlspecialchars($user->email ?? '-') ?></p>
                <p><strong>Bio:</strong> <?= htmlspecialchars($vendor->descrizione ?? 'N/A') ?></p>
			</div>

			<div id="venduti-content" class="tab-content" style="display: none;">
						
                <div class="orders">
                    <h3>Orders</h3>
                    <?php if($orders->isEmpty()): ?>
                    <p>No orders found.</p>
                    <?php else: ?>
                        <table class="table table-striped mb-0 orders-table">
                            <thead>
                                <tr>
                                    <th>Acquirente</th>
                                    <th>Prodotto</th>
                                    <th>Status</th>
                                    <th>Totale</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($orders as $order): ?>
                                    <tr>
                                        <td><?= htmlspecialchars($order->utente->username ?? ($order->utente->nome ?? '')) ?></td>
                                        <td>
                                            <?php if ($order->prodotto): ?>
                                                <a href="./product.php?id=<?= $order->prodotto->id ?>" class="text-decoration-none">
                                                    <?= htmlspecialchars($order->prodotto->nome) ?>
                                                </a>
                                            <?php else: ?>
                                                Prodotto
                                            <?php endif; ?>
                                        </td>
                                        <td><?= htmlspecialchars($order->status) ?></td>
                                        <td><?= mapPriceToRange($order->prezzo_totale, $priceRanges) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
			</div>

            <div id="prodotti-content" class="tab-content" style="display: none;">
						
                <div class="prodotti">
                    <h3>Prodotti</h3>
                    <?php if($prodotti->isEmpty()): ?>
                    <p>No prodotti found.</p>
                    <?php else: ?>
                        <table class="table table-striped mb-0 prodotti-table">
                            <thead>
                                <tr>
                                    <th>Prodotto</th>
                                    <th>Tipo</th>
                                    <th>Intensità</th>
                                    <th>Prezzo</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($prodotti as $prodotto): ?>
                                    <tr>
                                        <td><a href="./product.php?id=<?= $prodotto->id ?>" class="text-decoration-none">
                                                    <?= htmlspecialchars($prodotto->nome) ?>
                                        </a></td>
                                        <td><?= htmlspecialchars($prodotto->tipo) ?></td>
                                        <td><?= htmlspecialchars($prodotto->intensita) ?></td>
                                        <td><?= htmlspecialchars($prodotto->prezzo) ?>€</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    <?php endif; ?>
                </div>
			</div>

		</div>
        <script>
            function switchTab(tab) {
                document.querySelectorAll('.tab-button').forEach(btn => btn.classList.remove('active'));
                document.querySelectorAll('.tab-content').forEach(div => div.style.display = 'none');
                document.getElementById(tab + '-btn').classList.add('active');
                document.getElementById(tab + '-content').style.display = 'block';
            }
            window.onload = () => switchTab('informazioni');
        </script>
    </body>
</html>
