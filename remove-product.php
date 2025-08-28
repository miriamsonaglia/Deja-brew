<?php
// Imposta intestazione per rispondere in JSON
header('Content-Type: application/json');

// Abilita CORS se necessario
header("Access-Control-Allow-Origin: *");

// Recupera e decodifica il corpo della richiesta
$input = json_decode(file_get_contents('php://input'), true);

// Verifica che i dati siano presenti
if (!isset($input['productID']) || !isset($input['type'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Dati mancanti']);
    exit;
}

$productID = htmlspecialchars($input['productID']);
$type = htmlspecialchars($input['type'] ?? 'desideri'); // Default to 'desideri'

// Validazione base
if (empty($productID) || empty($type)) {
    http_response_code(400);
    echo json_encode(['error' => 'Dati non validi']);
    exit;
}

require_once('./bootstrap.php');
require_once('./Models/Lista.php');
require_once('./Models/UtenteCompratore.php');
use App\Models\Lista;
use App\Models\UtenteCompratore;
session_start();
$utenteCompratore = UtenteCompratore::where('id_utente', $_SESSION['LoggedUser']['id'])->first();

$productInCart = Lista::where('id_utente_compratore', $utenteCompratore->id)
                    ->where('id_prodotto', $productID)
                    ->where('tipo', $type)
                    ->first();

if($productInCart) {
    $productInCart->delete();
    // Risposta al client
    echo json_encode([
        'success' => true,
        'message' => 'Prodotto rimosso da ' . $type
    ]);
} else {
    http_response_code(404); // Not Found
    echo json_encode(['error' => 'Prodotto non trovato.']);
}
?>
