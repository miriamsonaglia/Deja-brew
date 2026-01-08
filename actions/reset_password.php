<?php

require_once __DIR__ . '/../bootstrap.php';
require_once __DIR__ . '/../Models/Utente.php';
use App\Models\Utente;

session_start();

    if (!isset($_SESSION['LoggedUser']['id'])) {
        header("Location: ../login.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';

        $errors = [];

        // Recupera l'utente
        $utente = Utente::find($_SESSION['LoggedUser']['id']);
        
        if (!$utente) {
            $errors[] = 'Utente non trovato.';
        } else {
            // Verifica la password attuale (Hashing incluso)
            if (!password_verify($current_password, $utente->password)) {
                $errors[] = 'Password attuale errata.';
            }
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            header("Location: ../profile.php");
            exit;
        }

        //Aggiorna la password
        $utente->update([
            'password' => password_hash($new_password, PASSWORD_DEFAULT)
        ]);

        // Aggiorna la password (Hashing incluso)
        //$utente->update([
        //    'password' => password_hash($new_password, PASSWORD_DEFAULT)
        //]);

        $_SESSION['success'] = 'Password aggiornata con successo.';
        header("Location: ../profile.php");
        exit;

    } else {
        // Se non POST, reindirizza
        header("Location: ../profile.php");
        exit;
    }
?>