<?php
    // Imposta intestazione per rispondere in JSON
    header('Content-Type: application/json');
    session_start();
    require_once('./bootstrap.php');
    require_once('./Models/Lista.php');
    require_once('./Models/UtenteCompratore.php');
    use App\Models\Lista;
    use App\Models\UtenteCompratore;
    // --- Recupero utente compratore senza usare la relazione ---
    $utenteCompratore = UtenteCompratore::where('id_utente', $_SESSION['LoggedUser']['id'])->first();

    if ($utenteCompratore) {
        $cartCount = Lista::where('id_utente_compratore', $utenteCompratore->id
                    )->where('tipo', 'carrello'
                    )->sum('quantita');
    } else {
        $cartCount = 0;
    }
    echo json_encode(['count' => $cartCount]);
?>