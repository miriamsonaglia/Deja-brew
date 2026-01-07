<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../Models/ImpostazioniUtente.php';
use App\Models\ImpostazioniUtente;

session_start();

if (!isset($_SESSION['LoggedUser']['id'])) {
    header("Location: ../login.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $notifiche = $_POST['notifiche'] ?? '';
    $tema = $_POST['tema'] ?? '';

    $errors = [];

    // Validazione
    if (!in_array($notifiche, ['on', 'off'])) {
        $errors[] = 'Valore notifiche non valido.';
    }
    if (!in_array($tema, ['chiaro', 'scuro'])) {
        $errors[] = 'Valore tema non valido.';
    }

    if ($errors) {
        $_SESSION['errors'] = $errors;
        header("Location: ../settings.php");
        exit;
    }

    // Converti valori
    $notifiche_val = $notifiche === 'on' ? 1 : 0;

    // Aggiorna le impostazioni
    ImpostazioniUtente::where('id_utente', $_SESSION['LoggedUser']['id'])->update([
        'notifiche' => $notifiche_val,
        'tema' => $tema
    ]);

    header("Location: ../settings.php?saved=1");
    exit;
} else {
    // Se non POST, reindirizza
    header("Location: ../settings.php");
    exit;
}
?>