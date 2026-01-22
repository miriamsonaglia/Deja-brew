<?php
    require_once __DIR__ . '/../bootstrap.php';
    require_once __DIR__ . '/../Models/Prodotto.php';
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

    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {

        $file = $_FILES['image'];

        // Temporary file path
        $tmpPath = $file['tmp_name'];

        // Validate MIME type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
        $mimeType = mime_content_type($tmpPath);

        if (!in_array($mimeType, $allowedTypes)) {
            die('Invalid image type');
        }

        // Upload directory
        $uploadsDir = realpath(__DIR__ . '/..') . '/uploads/prodotti';
        if (!is_dir($uploadsDir)) {
            mkdir($uploadsDir, 0755, true);
        }

        // Get file extension safely
        $extension = pathinfo($file['name'], PATHINFO_EXTENSION);

        // Generate unique filename
        $filename = uniqid('product_', true) . '.' . $extension;

        // Move uploaded file
        if (!move_uploaded_file($tmpPath, "$uploadsDir/$filename")) {
            die('Failed to move uploaded file');
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
        
        //Save old image filename to delete later
        $oldImage = $product->fotografia;

        // Edit the product        
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
        
        // Delete old image file if a new one was uploaded ($filename existing means a new file was uploaded)
        if (isset($filename) && $oldImage) {
            $oldImagePath = realpath(__DIR__ . '/..') . '/uploads/prodotti/' . $oldImage;
            if (file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }
        }

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