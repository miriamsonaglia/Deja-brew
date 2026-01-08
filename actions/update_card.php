<?php
    require_once __DIR__ . '/../bootstrap.php';

    use App\Models\CartaDiCredito;

    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('Location: ../profile.php');
        exit;
    }

    CartaDiCredito::where('id_utente',$_SESSION['LoggedUser']['id'])->where('id', $_POST['card_id'])->update([
        'circuito_pagamento'  => $_POST['circuito_pagamento'],
        'codice_carta' => $_POST['codice_carta'],
        'cvv_carta' => $_POST['cvv_carta'],
    ]);

    header('Location: ../profile.php');
    exit;
?>