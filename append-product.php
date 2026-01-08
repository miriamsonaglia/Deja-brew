<?php
// Imposta intestazione per rispondere in JSON
header('Content-Type: application/json');
header("Access-Control-Allow-Origin: *");

// Recupera e decodifica il corpo della richiesta POST (JSON)
$input = json_decode(file_get_contents('php://input'), true);

if (!$input || !isset($input['productID']) || !isset($input['quantity'])) {
    http_response_code(400);
    echo json_encode(['error' => 'Dati mancanti: productID o quantity']);
    exit;
}

$productID = (int)$input['productID']; // ID prodotto deve essere intero
$quantity  = max(1, (int)$input['quantity']); // Almeno 1
$type      = in_array($input['type'] ?? '', ['carrello', 'desideri']) ? $input['type'] : 'desideri';

if ($productID <= 0) {
    http_response_code(400);
    echo json_encode(['error' => 'ID prodotto non valido']);
    exit;
}

// Avvia sessione e carica bootstrap + modelli
session_start();

if (!isset($_SESSION['LoggedUser']['id'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Utente non autenticato']);
    exit;
}

require_once __DIR__ . '/bootstrap.php';
use App\Models\Lista;
use App\Models\UtenteCompratore;

// Recupera l'utente compratore legato all'utente loggato
$utenteCompratore = UtenteCompratore::where('id_utente', $_SESSION['LoggedUser']['id'])->first();

if (!$utenteCompratore) {
    http_response_code(404);
    echo json_encode(['error' => 'Profilo compratore non trovato']);
    exit;
}

$idUtenteCompratore = $utenteCompratore->id;

// --- LOGICA PRINCIPALE: upsert (update or insert) ---
$existing = Lista::where([
    'id_utente_compratore' => $idUtenteCompratore,
    'id_prodotto'          => $productID,
    'tipo'                 => $type
])->first();

if ($existing) {
    // Prodotto già presente → aggiorna quantità
    $existing->quantita += $quantity;
    $existing->save();
    $message = "Quantità aggiornata nel " . ($type === 'carrello' ? 'carrello' : 'lista desideri');
} else {
    // Prodotto non presente → crea nuovo record
    Lista::create([
        'id_utente_compratore' => $idUtenteCompratore,
        'id_prodotto'          => $productID,
        'tipo'                 => $type,
        'quantita'             => $quantity
    ]);
    $message = "Prodotto aggiunto alla " . ($type === 'carrello' ? 'carrello' : 'lista desideri');
}

// Risposta di successo
echo json_encode([
    'success' => true,
    'message' => $message,
    'type'    => $type,
    'productID' => $productID,
    'quantity'  => $quantity
]);