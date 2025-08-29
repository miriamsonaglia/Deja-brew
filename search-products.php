<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Models/Prodotto.php';

use App\Models\Prodotto;

if (isset($_GET['q']) && strlen(trim($_GET['q'])) > 0) {
    $search = trim($_GET['q']);
    
    // Cerca prodotti per nome, case-insensitive
    $products = Prodotto::where('nome', 'LIKE', '%' . $search . '%')->limit(10)->get();

    header('Content-Type: application/json');
    echo json_encode($products->toArray());
}
