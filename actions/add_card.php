<?php
    require_once __DIR__ . '/../bootstrap.php';
    require_once __DIR__ . '/../Models/CartaDiCredito.php';
    use App\Models\CartaDiCredito;

    session_start();

    if (!isset($_SESSION['LoggedUser']['id'])) {
        header("Location: ../login.php");
        exit;
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $card_name = trim($_POST['card_name'] ?? '');
        $circuito = trim($_POST['circuito_pagamento'] ?? '');
        $card_number = trim($_POST['card_number'] ?? '');
        $expiry = trim($_POST['scadenza'] ?? '');
        $cvv = trim($_POST['cvv'] ?? '');

        $errors = [];

        // Validazione nome titolare
        if (empty($card_name)) {
            $errors[] = 'Il nome del titolare è obbligatorio.';
        }

        // Validazione numero carta (semplice: 13-19 cifre)
        if (empty($card_number) || !preg_match('/^\d{13,19}$/', str_replace(' ', '', $card_number))) {
            $errors[] = 'Numero carta non valido.';
        }

        // Validazione scadenza (MM/YY)
        if (empty($expiry) || !preg_match('/^(0[1-9]|1[0-2])\/\d{2}$/', $expiry)) {
            $errors[] = 'Data di scadenza non valida (formato MM/YY).';
        } else {
            list($month, $year) = explode('/', $expiry);
            $currentYear = date('y');
            $currentMonth = date('m');
            if ($year < $currentYear || ($year == $currentYear && $month < $currentMonth)) {
                $errors[] = 'La carta è scaduta.';
            }
        }

        // Validazione CVV (3-4 cifre)
        if (empty($cvv) || !preg_match('/^\d{3,4}$/', $cvv)) {
            $errors[] = 'CVV non valido.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            header("Location: ../cards-and-payment.php");
            exit;
        }

        // Inserisci nel database
        CartaDiCredito::create([
            'id_utente' => $_SESSION['LoggedUser']['id'],
            //'nome_titolare' => $card_name,
            'codice_carta' => str_replace(' ', '', $card_number), // Rimuovi spazi
            'circuito_pagamento' => $circuito,
            //'scadenza_mese' => $month,
            //'scadenza_anno' => $year,
            'cvv_carta' => $cvv
        ]);

        $_SESSION['success'] = 'Carta aggiunta con successo.';
        header("Location: ../cards-and-payment.php");
        exit;
    } else {
        // Se non POST, reindirizza
        header("Location: ../cards-and-payment.php");
        exit;
    }
?>