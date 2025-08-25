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

                <button type="button" class="btn btn-link mb-3" data-bs-toggle="modal" data-bs-target="#modalNuovaCarta">
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

<!-- Modal per inserimento nuova carta -->
<div class="modal fade" id="modalNuovaCarta" tabindex="-1" aria-labelledby="modalNuovaCartaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formNuovaCarta">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNuovaCartaLabel">Aggiungi nuova carta di credito</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="circuito" class="form-label">Circuito della carta</label>
            <select id="circuito" class="form-select" required>
              <option value="">Seleziona</option>
              <option value="Visa">Visa</option>
              <option value="MasterCard">MasterCard</option>
              <option value="American Express">American Express</option>
              <option value="Maestro">Maestro</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="numeroCarta" class="form-label">Numero carta</label>
            <input type="text" class="form-control" id="numeroCarta" placeholder="1234 5678 9012 3456" required pattern="\d{4} \d{4} \d{4} \d{4}">
          </div>
          <div class="mb-3">
            <label for="scadenza" class="form-label">Data scadenza</label>
            <input type="month" class="form-control" id="scadenza" required>
          </div>
          <div class="mb-3">
            <label for="cvvNuova" class="form-label">CVV</label>
            <input type="password" class="form-control" id="cvvNuova" placeholder="***" required pattern="\d{3,4}">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Chiudi</button>
          <button type="submit" class="btn btn-success  ">Aggiungi carta</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
// Gestione submit del form nuova carta
document.getElementById('formNuovaCarta').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const circuito = document.getElementById('circuito').value;
    const numero = document.getElementById('numeroCarta').value;
    const scadenza = document.getElementById('scadenza').value;
    const cvv = document.getElementById('cvvNuova').value;
    
    if(circuito && numero && scadenza && cvv) {
        // Qui puoi aggiungere la chiamata AJAX per salvare la carta nel DB
        alert('Carta aggiunta con successo! (demo)');

        // Chiudi il modal
        const modal = bootstrap.Modal.getInstance(document.getElementById('modalNuovaCarta'));
        modal.hide();

        // Aggiorna select con la nuova carta (demo)
        const select = document.getElementById('carta');
        const newOption = document.createElement('option');
        newOption.value = Date.now(); // id fittizio
        newOption.text = `${circuito} - **** **** **** ${numero.slice(-4)}`;
        select.add(newOption);
        select.value = newOption.value;
    } else {
        alert('Compila tutti i campi.');
    }
});
</script>

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
