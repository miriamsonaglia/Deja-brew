<?php

    require_once __DIR__ . '/../bootstrap.php';
    use App\Models\CartaDiCredito;

    session_start();


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

    try {
        // Verify card belongs to current user
        $card = CartaDiCredito::where('id', $card_id)->first();

        if (!$card || $card->id_utente != $user_id) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            
            header('Location: ../cards-and-payment.php');
            exit;
        }
        
        // Delete the card
        CartaDiCredito::where('id', $card_id)->delete();
        
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