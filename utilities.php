<?php
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Models/Lista.php';
use App\Models\Lista;
function wished($productID, $userID) {
    $wishlist = Lista::where('id_utente_compratore', $userID)
                    ->where('tipo', 'desideri')
                    ->where('id_prodotto', $productID)
                    ->first();
    return $wishlist !== null;
}
?>