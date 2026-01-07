<?php

    require_once __DIR__ . '/../bootstrap.php';
    require_once __DIR__ . '/../Models/UtenteVenditore.php';
    require_once __DIR__ . '/../role.php';

    use App\Models\UtenteVenditore;


    session_start();

    if (!isset($_SESSION['LoggedUser']['id']) || $_SESSION['UserRole'] !== Role::VENDOR->value) {
        header("Location: login.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $descrizione = trim($_POST['descrizione'] ?? '');

        $errors = [];

        if (empty($descrizione)) {
            $errors[] = 'La descrizione non può essere vuota.';
        } elseif (strlen($descrizione) > 500) {  // Limite random
            $errors[] = 'La descrizione è troppo lunga (max 500 caratteri).';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            header("Location: ../profile.php");
            exit;
        }

        // Aggiorna la descrizione
        UtenteVenditore::where('id', $_SESSION['LoggedUser']['id'])->update([
            'descrizione' => $descrizione
        ]);

        $_SESSION['success'] = 'Descrizione aggiornata con successo.';
        header("Location: ../profile.php");
        exit;
    } else {
        // Se non POST, reindirizza
        header("Location: profile.php");
        exit;
    }
?>