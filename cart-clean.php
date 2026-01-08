<?php
// TODO: VALUTA SE CREARE UNA CLASSE DI GESTIONE DEL CARRELLO
// Imposta intestazione per rispondere in JSON
header('Content-Type: application/json');

// Abilita CORS se necessario
header("Access-Control-Allow-Origin: *");

require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Models/Lista.php';
require_once __DIR__ . '/Models/UtenteCompratore.php';
use App\Models\Lista;
use App\Models\UtenteCompratore;

session_start();
if (!isset($_SESSION['LoggedUser']['id'])) {
    http_response_code(401);
    echo json_encode([
        'success' => false,
        'message' => 'Utente non autenticato',
    ]);
    exit;
}
// --- Recupero utente compratore senza usare la relazione ---
$utenteCompratore = UtenteCompratore::where('id_utente', $_SESSION['LoggedUser']['id'])->first();
$productsInCart = collect();

if ($utenteCompratore) {
    $productsInCart = Lista::where('id_utente_compratore', $utenteCompratore->id)
        ->where('tipo', 'carrello')
        ->get();

    foreach ($productsInCart as $productInCart) {
        $productInCart->delete();
    }
}

echo json_encode([
    'success' => true,
    'message' => 'Prodotti rimossi dal carrello',
    'removed' => $productsInCart->count(),
]);
?>
