<?php

    require_once __DIR__ . '/../bootstrap.php';

var_dump(class_exists(\App\Models\CartaDiCredito::class));
exit;


    use App\Models\CartaDiCredito;

    session_start();


    if (!isset($_SESSION['LoggedUser']['id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);
        exit;
    }

    if (!isset($_POST['card_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'card_id is required']);
        exit;
    }

    if (!isset($_POST['cvv_carta'])) {
        http_response_code(400);
        echo json_encode(['error' => 'cvv_carta is required']);
        exit;
    }

    $user_id = $_SESSION['LoggedUser']['id'];
    $card_id = $_POST['card_id'];
    $cvv_carta = $_POST['cvv_carta'];

    try {
        // Verify card belongs to current user
        $card = CartaDiCredito::where('id', $card_id)->first();
        
        if (!$card || $card->user_id != $user_id) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            exit;
        }
        
        // Delete the card
        CartaDiCredito::where('id', $card_id)->delete();
        
        echo json_encode(['success' => true]);
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
    }
?>