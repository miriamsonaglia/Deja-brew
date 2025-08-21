<?php
// TODO: VALUTA SE CREARE UNA CLASSE DI GESTIONE DEL CARRELLO
// Imposta intestazione per rispondere in JSON
header('Content-Type: application/json');

// Abilita CORS se necessario
// header("Access-Control-Allow-Origin: *");


require_once('./Models/Lista.php');
use App\Models\Lista;

session_start();
$productsInCart = Lista::where('id_utente_compratore', $_SESSION['UserID'])
    ->where('tipo', 'carrello')
    ->get();

foreach($productsInCart as $productInCart) {
    $productInCart->delete();
}
echo json_encode([
    'success' => true,
    'message' => 'Prodotti rimosso dal carrello',
]);
?>
