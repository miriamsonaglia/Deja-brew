<?php
require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../Models/Utente.php';
use App\Models\Utente;
session_start();    

if (!isset($_FILES['image'])) {
    die('No file uploaded');
}

$file = $_FILES['image'];

if ($file['error'] !== UPLOAD_ERR_OK) {
    die('Upload error');
}

// Basic validation
$allowedTypes = ['image/jpeg', 'image/png', 'image/webp'];
if (!in_array(mime_content_type($file['tmp_name']), $allowedTypes)) {
    die('Invalid image type');
}

// Destination
$uploadsDir = realpath(__DIR__ . '/..') . '/uploads/profile_images';
if (!is_dir($uploadsDir)) {
    mkdir($uploadsDir, 0755, true);
}

$extension = pathinfo($file['name'], PATHINFO_EXTENSION);
$filename = uniqid('profile_', true) . '.' . $extension;

move_uploaded_file($file['tmp_name'], "$uploadsDir/$filename");

$userId = $_SESSION['LoggedUser']['id'];

//Old image to be deleted
$user = Utente::where('id', $userId)->first();
$oldImage = $user->immagine_profilo;

$user->immagine_profilo = $filename;
$user->save();
// Delete old image file
if ($oldImage && $oldImage !== $user->immagine_profilo) {
    $oldImagePath = realpath(__DIR__ . '/..') . '/uploads/profile_images/' . $oldImage;
    if (file_exists($oldImagePath)) {
        unlink($oldImagePath);
    }
}

header("Location: ../profile-settings.php?upload=success");
