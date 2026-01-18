<?php
    require_once __DIR__ . '/../bootstrap.php';
    use App\Models\Prodotto;
    session_start();

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        $redirect = $_SERVER['HTTP_REFERER'] ?? '/index.php';
        header("Location: $redirect");
        exit;
    }
          
    
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
    

    if (isset($_FILES['image'])) {

        $file = $_FILES['image'];
        if ($file['error'] === UPLOAD_ERR_OK) {
    
            $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
            if (in_array(mime_content_type($_FILES['image']['tmp_name']), $allowedTypes)) {
                
            $uploadsDir = realpath(__DIR__ . '/..') . '/uploads/profile_images';
            if (!is_dir($uploadsDir)) {
                mkdir($uploadsDir, 0755, true);
            }

            $extension = pathinfo($file['name'], PATHINFO_EXTENSION);
            $filename = uniqid('product_', true) . '.' . $extension;

            move_uploaded_file($file['tmp_name'], "$uploadsDir/$filename");
    
            }

        }
    }

        
    
    $user_id = $_SESSION['LoggedUser']['id'];
    $product_id = $_POST['product_id'];
    
    
    try {
        // Verify card belongs to current user
        
        $product = Prodotto::where('id', $product_id)->first();
        if ($product->venditore->id_utente != $user_id) {
            http_response_code(response_code: 403);
            echo json_encode(['error' => 'Forbidden']);
            
            $redirect = $_SERVER['HTTP_REFERER'] ?? '/index.php';
            header("Location: $redirect");
            exit;
        }
        
        if (!$product) {
            http_response_code(204);
            echo json_encode(['error' => 'Resource not found in database']);
                
            $redirect = $_SERVER['HTTP_REFERER'] ?? '/index.php';
            header("Location: $redirect");
            exit;
        }
        

        // Edit the card
        
        $product->update([
            'nome' => $_POST['product_nome'],
            'prezzo' => $_POST['product_prezzo'],
            'peso' => $_POST['product_peso'],
            'provenienza' => $_POST['product_provenienza'],
            'tipo' => $_POST['product_tipo'],
            'intensita' => $_POST['product_intensita'],
            'categoria_id' => $_POST['product_categoria'],
            'aroma_id' => $_POST['product_aroma'],
            'fotografia' => $filename ?? $product->fotografia,
            
        ]);
        echo json_encode(['success' => true]);
        
        $redirect = $_SERVER['HTTP_REFERER'] ?? '/index.php';
        header("Location: $redirect");
        exit;
    } catch (Exception $e) {
        http_response_code(500);
        echo json_encode(['error' => 'Database error']);
        
        $redirect = $_SERVER['HTTP_REFERER'] ?? '/index.php';
        header("Location: $redirect");
        exit;
    }
?>