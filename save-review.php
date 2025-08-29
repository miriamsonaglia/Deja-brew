<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Models/Recensione.php';

use App\Models\Recensione;

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idProdotto = $_POST['id_prodotto'];
    $idUtente   = $_POST['id_utente'];
    $stelle     = $_POST['stelle'];
    $testo      = $_POST['testo'];

    // Controllo se esiste già una recensione di questo utente per il prodotto
    $esiste = Recensione::where('id_prodotto', $idProdotto)
        ->where('id_utente', $idUtente)
        ->first();

    if ($esiste) {
        die("Hai già recensito questo prodotto.");
    }

    Recensione::create([
        'id_prodotto' => $idProdotto,
        'id_utente'   => $idUtente,
        'stelle'      => $stelle,
        'testo'       => $testo
    ]);

    header("Location: product.php?id=" . $idProdotto);
    exit;
}
