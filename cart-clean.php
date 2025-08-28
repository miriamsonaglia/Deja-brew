<?php
// TODO: VALUTA SE CREARE UNA CLASSE DI GESTIONE DEL CARRELLO
// Imposta intestazione per rispondere in JSON
header('Content-Type: application/json');

// Abilita CORS se necessario
header("Access-Control-Allow-Origin: *");


require_once('./Models/Lista.php');
require_once('./Models/UtenteCompratore.php');
use App\Models\Lista;
use App\Models\UtenteCompratore;

session_start();
// --- Recupero utente compratore senza usare la relazione ---
$utenteCompratore = UtenteCompratore::where('id_utente', $_SESSION['LoggedUser']['id'])->first();
$productsInCart = Lista::where('id_utente_compratore', $utenteCompratore->id)
    ->where('tipo', 'carrello')
    ->get();

foreach($productsInCart as $productInCart) {
    $productInCart->delete();
}
echo json_encode([
    'success' => true,
    'message' => 'Prodotti rimossi dal carrello',
]);
?>
