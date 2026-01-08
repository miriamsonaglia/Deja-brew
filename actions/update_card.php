<?php
    require_once __DIR__ . '/../bootstrap.php';
    use App\Models\CartaDiCredito;
    session_start();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../cards-and-payment.php');
        exit;
    }
          
    if (!isset($_SESSION['LoggedUser']['id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        header('Location: ../cards-and-payment.php');
        exit;
    }
    if (!isset($_POST['card_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'card_id is required']);
        header('Location: ../cards-and-payment.php');
        exit;
    }
    
    if (!isset($_POST['cvv_carta'])) {
        http_response_code(400);
        echo json_encode(['error' => 'cvv_carta is required']);
        header('Location: ../cards-and-payment.php');
        exit;
    }
    
    $user_id = $_SESSION['LoggedUser']['id'];
    $card_id = $_POST['card_id'];
    $cvv_carta = $_POST['cvv_carta'];
    [$mese, $anno] = explode('-', $_POST['scadenza']);
    
    
    try {
        // Verify card belongs to current user
        $card = CartaDiCredito::where('id', $card_id)->first();
        
        if (!$card) {
            http_response_code(204);
            echo json_encode(['error' => 'Resource not found in database']);
            
            header('Location: ../cards-and-payment.php');
            exit;
        }
        
        if ($card->id_utente != $user_id) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            
            header('Location: ../cards-and-payment.php');
            exit;
        }

        
        // Edit the card
        $card = CartaDiCredito::where('id_utente',$_SESSION['LoggedUser']['id'])
                                ->where('id', $card_id)
                                ->firstOrFail();
        $card->update([
            'circuito_pagamento'  => $_POST['circuito_pagamento'],
            'codice_carta' => $_POST['codice_carta'],
            'cvv_carta' => $_POST['cvv_carta'],
            'scadenza_mese' => $mese,
            'scadenza_anno' => $anno,
            
        ]);
        echo json_encode(['success' => true]);

        header('Location: ../cards-and-payment.php');
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
        
        header('Location: ../cards-and-payment.php');
        exit;
    }
?>