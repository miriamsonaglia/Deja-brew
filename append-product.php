<?php
// TODO: VALUTA SE CREARE UNA CLASSE DI GESTIONE DEL CARRELLO
// Imposta intestazione per rispondere in JSON
header('Content-Type: application/json');

// Abilita CORS se necessario
header("Access-Control-Allow-Origin: *");


// Recupera e decodifica il corpo della richiesta
$input = json_decode(file_get_contents('php://input'), true);

// Verifica che i dati siano presenti
if (!isset($input['productID']) || !isset($input['quantity'])) {
    http_response_code(400); // Bad Request
    echo json_encode(['error' => 'Dati mancanti']);
    exit;
}

$productID = htmlspecialchars($input['productID']);
$quantity = (int)$input['quantity'];
$type = htmlspecialchars($input['type'] ?? 'desideri'); // Default to 'desideri'

// Validazione base
if ($quantity <= 0 || empty($productID)) {
    http_response_code(400);
    echo json_encode(['error' => 'Dati non validi']);
    exit;
}

require_once('./bootstrap.php');
require_once('./Models/Lista.php');
use App\Models\Lista;

session_start();
$productInCart = new Lista();
$productInCart->id_utente_compratore = $_SESSION['LoggedUser']['id'];
$productInCart->id_prodotto = $productID;
$productInCart->tipo = $type;
$productInCart->quantita = $quantity;
$productInCart->save();

// Risposta al client
echo json_encode([
    'success' => true,
    'message' => 'Prodotto aggiunto correttamente alla lista ' . $type,
]);
?>
