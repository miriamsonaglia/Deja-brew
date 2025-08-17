<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Models/CartaDiCredito.php';

use App\Models\CartaDiCredito;

// MOCK utente e carrello
$totale = 25.50; // esempio totale ordine
$carrello = [
    ['nome' => 'Caffè Arabica', 'quantita' => 1, 'prezzo' => 12.50],
    ['nome' => 'Caffè Robusta', 'quantita' => 1, 'prezzo' => 13.00],
];


// VERSIONE ELOQUENT (quando il DB sarà popolato)

// $idUtente = 1; // esempio ID utente loggato
// $carte = CartaDiCredito::where('id_utente', $idUtente)->get();

// ---------------------------------------------------------------------------

// MOCK carte di credito
$carte = [
    (object)[
        'id' => 1,
        'circuito_pagamento' => 'Visa',
        'codice_carta' => '**** **** **** 1234',
        'cvv_carta' => '***'
    ],
    (object)[
        'id' => 2,
        'circuito_pagamento' => 'MasterCard',
        'codice_carta' => '**** **** **** 5678',
        'cvv_carta' => '***'
    ]
];
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Checkout - Deja-brew</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
</head>
<body>

<div class="container my-5">
    <h2 class="mb-4">Checkout</h2>

    <!-- Carrello -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-bold">Riepilogo ordine</div>
        <ul class="list-group list-group-flush">
            <?php foreach ($carrello as $item): ?>
            <li class="list-group-item d-flex justify-content-between">
                <span><?php echo $item['nome'] . ' x ' . $item['quantita']; ?></span>
                <span><?php echo number_format($item['prezzo'], 2); ?> €</span>
            </li>
            <?php endforeach; ?>
            <li class="list-group-item d-flex justify-content-between fw-bold">
                <span>Totale</span>
                <span><?php echo number_format($totale, 2); ?> €</span>
            </li>
        </ul>
    </div>

    <!-- Pagamento -->
    <div class="card shadow-sm">
        <div class="card-header fw-bold">Metodo di pagamento</div>
        <div class="card-body">
            <form id="checkoutForm">
                <div class="mb-3">
                    <label for="carta" class="form-label">Seleziona carta di credito</label>
                    <select class="form-select" id="carta">
                        <?php foreach ($carte as $carta): ?>
                        <option value="<?php echo $carta->id; ?>">
                            <?php echo $carta->circuito_pagamento . ' - ' . $carta->codice_carta; ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <button type="button" class="btn btn-link mb-3" id="aggiungiCarta">
                    <i class="bi bi-plus-circle"></i> Aggiungi nuova carta
                </button>

                <div class="mb-3">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="password" class="form-control" id="cvv" placeholder="***">
                </div>

                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-credit-card"></i> Paga <?php echo number_format($totale, 2); ?> €
                </button>
            </form>
        </div>
    </div>

    <div id="esitoPagamento" class="alert mt-4 d-none"></div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script>
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    e.preventDefault();
    const carta = document.getElementById('carta').value;
    const cvv = document.getElementById('cvv').value;

    const esito = document.getElementById('esitoPagamento');
    if(carta && cvv) {
        esito.className = 'alert alert-success mt-4';
        esito.textContent = 'Pagamento effettuato con successo!';
    } else {
        esito.className = 'alert alert-danger mt-4';
        esito.textContent = 'Errore: compilare tutti i campi.';
    }
    esito.classList.remove('d-none');
});

// Pulsante aggiungi nuova carta (apre prompt per demo)
document.getElementById('aggiungiCarta').addEventListener('click', function() {
    alert("Qui potrai aprire un form per aggiungere una nuova carta.");
});
</script>

</body>
</html>
