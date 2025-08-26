<?php
    // Imposta intestazione per rispondere in JSON
    header('Content-Type: application/json');
    session_start();
    require_once('./bootstrap.php');
    require_once('./Models/Lista.php');
    use App\Models\Lista;
    $userID = $_SESSION['LoggedUser']['id'] ?? null;
    if ($userID) {
        $cartCount = Lista::where('id_utente_compratore', $userID
                    )->where('tipo', 'carrello'
                    )->sum('quantita');
    } else {
        $cartCount = 0;
    }
    echo json_encode(['count' => $cartCount]);
?>