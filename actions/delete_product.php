<?php

    require_once __DIR__ . '/../bootstrap.php';
    require_once __DIR__ . '/../Models/Prodotto.php';
    use App\Models\Prodotto;
    session_start();


    if (!isset($_SESSION['LoggedUser']['id'])) {
        http_response_code(401);
        echo json_encode(['error' => 'Unauthorized']);

        $redirect = $_SERVER['HTTP_REFERER'] ?? '/index.php';
        header("Location: $redirect");
        exit;
    }

    if (!isset($_POST['product_id'])) {
        http_response_code(400);
        echo json_encode(['error' => 'product_id is required']);
        
        $redirect = $_SERVER['HTTP_REFERER'] ?? '/index.php';
        header("Location: $redirect");
        exit;
    }

    $user_id = $_SESSION['LoggedUser']['id'];
    $product_id = $_POST['product_id'];

    try {
        // Verify product belongs to current user
        $product = Prodotto::where('id', $product_id)->first();
        if ($product->venditore->id_utente != $user_id) {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden']);
            
            $redirect = $_SERVER['HTTP_REFERER'] ?? '/index.php';
            header("Location: $redirect");
            exit;
        }

        // Delete the product
        $prodotto = Prodotto::where('id', $product_id)->first();
        
        if ($prodotto->fotografia!=null) {
            $oldImagePath = realpath(__DIR__ . '/..') . '/uploads/prodotti/' . $prodotto->fotografia;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }
        $prodotto->delete();

        echo json_encode(['success' => true]);

        header("Location: /home.php");
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
        
        $redirect = $_SERVER['HTTP_REFERER'] ?? '/index.php';
        header("Location: $redirect");
        exit;
    }
?>