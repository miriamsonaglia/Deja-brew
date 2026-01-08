<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Models/Utente.php';
require_once __DIR__ . '/Models/UtenteCompratore.php';
require_once __DIR__ . '/Models/CartaDiCredito.php';
require_once __DIR__ . '/Models/Lista.php';
require_once __DIR__ . '/Models/Prodotto.php';
require_once __DIR__ . '/Models/Ordine.php';

use App\Models\Utente;
use App\Models\UtenteCompratore;
use App\Models\CartaDiCredito;
use App\Models\Lista;
use App\Models\Prodotto;
use App\Models\Ordine;

session_start();


// --- Controllo utente loggato ---
if (!isset($_SESSION['LoggedUser'])) {
    die("Devi effettuare il login per accedere al checkout.");
}

$idUtente = $_SESSION['LoggedUser']['id'];
$utente = Utente::find($idUtente);
if (!$utente) die("Utente non trovato.");

// --- Recupero utente compratore senza usare la relazione ---
$utenteCompratore = UtenteCompratore::where('id_utente', $idUtente)->first();
if (!$utenteCompratore) die("Utente compratore non trovato.");

// --- Verifica se è un acquisto diretto da product.php ---
$isBuyNow = isset($_POST['buy_now']) && $_POST['buy_now'] == '1';
$idProdotto = $_POST['id_prodotto'] ?? null;
$quantita = intval($_POST['quantita'] ?? 1);

if ($isBuyNow && $idProdotto) {
    // Acquisto diretto di un singolo prodotto
    $prodotto = Prodotto::find($idProdotto);
    if (!$prodotto) die("Prodotto non trovato.");
    
    $carrello = collect([(object)[
        'prodotto' => $prodotto,
        'quantita' => $quantita
    ]]);
    
    $totale = $quantita * $prodotto->prezzo;
} else {
    // Acquisto dal carrello
    $carrello = Lista::where('id_utente_compratore', $utenteCompratore->id)
                     ->carrello()
                     ->with('prodotto')
                     ->get();

    // Calcolo totale
    $totale = $carrello->sum(function($item) {
        return $item->quantita * $item->prodotto->prezzo;
    });
}

$iva = $totale * 0.22;
$totaleFinale = $totale + $iva;

// --- Recupero carte di credito ---
$carte = CartaDiCredito::where('id_utente', $idUtente)->get();

// --- Gestione pagamento ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'pay') {
    $idCarta = $_POST['id_carta'] ?? '';
    $cvvInserito = $_POST['cvv'] ?? '';

    $errors = [];

    // Valida che la carta sia stata selezionata
    if (!$idCarta) {
        $errors[] = "Seleziona una carta di credito.";
    } else {
        // Recupera la carta dal database
        $carta = CartaDiCredito::where('id', $idCarta)
                               ->where('id_utente', $idUtente)
                               ->first();
        
        if (!$carta) {
            $errors[] = "Carta non trovata o non autorizzata.";
        } else {
            // Valida il CVV inserito
            if (!$cvvInserito) {
                $errors[] = "Inserisci il CVV della carta.";
            } elseif (!preg_match('/^\d{3,4}$/', $cvvInserito)) {
                $errors[] = "CVV non valido (deve essere di 3 o 4 cifre).";
            } elseif ($cvvInserito !== $carta->cvv_carta) {
                $errors[] = "CVV errato. Verifica il codice inserito.";
            }
        }
    }

    if ($errors) {
        $_SESSION['error'] = implode('<br>', $errors);
        header('Location: checkout.php');
        exit;
    }

    // Se tutto è corretto, procedi con il pagamento
    $_SESSION['success'] = "Pagamento effettuato con successo!";
    
    try {
        if ($isBuyNow) {
            // Acquisto diretto - crea un ordine per il singolo prodotto
            $ordine = new Ordine();
            $ordine->id_utente = $idUtente;
            $ordine->id_prodotto = $idProdotto;
            $ordine->quantita = $quantita;
            $ordine->prezzo_totale = $totaleFinale;
            $ordine->status = 'confermato';
            $ordine->save();
        } else {
            // Acquisto dal carrello - crea un ordine per ogni prodotto
            foreach ($carrello as $item) {
                $ordine = new Ordine();
                $ordine->id_utente = $idUtente;
                $ordine->id_prodotto = $item->id_prodotto;
                $ordine->quantita = $item->quantita;
                $ordine->prezzo_totale = $item->quantita * $item->prodotto->prezzo;
                $ordine->status = 'confermato';
                $ordine->save();
            }
            
            // Svuota il carrello dopo aver creato gli ordini
            Lista::where('id_utente_compratore', $utenteCompratore->id)
                 ->carrello()
                 ->delete();
        }
    } catch (Exception $e) {
        $_SESSION['error'] = "Errore durante il salvataggio dell'ordine: " . $e->getMessage();
        header('Location: checkout.php');
        exit;
    }
    
    header('Location: orders-buyer.php');
    exit;
}

// --- Gestione aggiunta nuova carta ---
if ($_SERVER['REQUEST_METHOD'] === 'POST' && ($_POST['action'] ?? '') === 'add_card') {
    $circuito = $_POST['circuito'] ?? '';
    $numero = $_POST['numero'] ?? '';
    $scadenza_mese = $_POST['scadenza_mese'] ?? '';
    $scadenza_anno = $_POST['scadenza_anno'] ?? '';
    $cvv = $_POST['cvv'] ?? '';

    $errors = [];

    // Validazioni lato server
    if (!$circuito) $errors[] = "Seleziona il circuito della carta.";
    $numeroPulito = str_replace(' ', '', $numero);
    if (!preg_match('/^\d{16}$/', $numeroPulito)) $errors[] = "Numero carta non valido.";
    if (!preg_match('/^\d{3,4}$/', $cvv)) $errors[] = "CVV non valido.";
    if (strtotime(date($scadenza_anno . '/' . $scadenza_mese)) > strtotime(date('Y-m'))) $errors[] = "La data di scadenza deve essere futura.";

    if ($errors) {
        $_SESSION['error'] = implode('<br>', $errors);
    } else {
        $carta = new CartaDiCredito();
        $carta->id_utente = $idUtente;
        $carta->circuito_pagamento = $circuito;
        $carta->codice_carta = $numeroPulito;
        $carta->cvv_carta = $cvv;
        $carta->nome_titolare = $utente->nome . ' ' . $utente->cognome; // FIXME SE NECESSARIO
        $carta->scadenza_mese = $scadenza_mese;
        $carta->scadenza_anno = $scadenza_anno;
        $carta->save();
        $_SESSION['success'] = "Carta aggiunta con successo!";
    }

    header('Location: checkout.php');
    exit;
}
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

    <!-- Messaggi -->
    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <?php if (isset($_SESSION['error'])): ?>
        <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

    <!-- Carrello -->
    <div class="card mb-4 shadow-sm">
        <div class="card-header fw-bold">Riepilogo ordine</div>
        <ul class="list-group list-group-flush">
            <?php if ($carrello->count() > 0): ?>
                <?php foreach ($carrello as $item): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <span><?= $item->prodotto->nome ?> x <?= $item->quantita ?></span>
                        <span><?= number_format($item->quantita * $item->prodotto->prezzo, 2) ?> €</span>
                    </li>
                <?php endforeach; ?>
                <li class="list-group-item d-flex justify-content-between fw-bold">
                    <span>Totale</span>
                    <span><?= number_format($totale, 2) ?> + <?= number_format($iva, 2) ?> €</span>
                </li>
            <?php else: ?>
                <li class="list-group-item text-center">Il carrello è vuoto.</li>
            <?php endif; ?>
        </ul>
    </div>

    <!-- Pagamento -->
    <div class="card shadow-sm">
        <div class="card-header fw-bold">Metodo di pagamento</div>
        <div class="card-body">
            <form method="POST" action="checkout.php">
                <input type="hidden" name="action" value="pay">
                <?php if ($isBuyNow): ?>
                    <input type="hidden" name="buy_now" value="1">
                    <input type="hidden" name="id_prodotto" value="<?= $idProdotto ?>">
                    <input type="hidden" name="quantita" value="<?= $quantita ?>">
                <?php endif; ?>
                <div class="mb-3">
                    <label for="carta" class="form-label">Seleziona carta di credito</label>
                    <select class="form-select" id="carta" name="id_carta" required>
                        <?php if ($carte->count() > 0): ?>
                            <?php foreach ($carte as $carta): ?>
                                <option value="<?= $carta->id ?>">
                                    <?= $carta->circuito_pagamento ?> - **** **** **** <?= substr($carta->codice_carta, -4) ?>
                                </option>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <option value="">Nessuna carta disponibile</option>
                        <?php endif; ?>
                    </select>
                </div>

                <button type="button" class="btn btn-link mb-3" data-bs-toggle="modal" data-bs-target="#modalNuovaCarta">
                    <i class="bi bi-plus-circle"></i> Aggiungi nuova carta
                </button>

                <div class="mb-3">
                    <label for="cvv" class="form-label">CVV</label>
                    <input type="password" class="form-control" id="cvv" name="cvv" placeholder="***" required>
                </div>

                <button type="submit" class="btn btn-success btn-lg">
                    <i class="bi bi-credit-card"></i> Paga <?= number_format($totaleFinale, 2) ?> €
                </button>
            </form>
        </div>
    </div>
</div>

<!-- Modal nuova carta -->
<div class="modal fade" id="modalNuovaCarta" tabindex="-1" aria-labelledby="modalNuovaCartaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formNuovaCarta" method="POST" action="checkout.php">
        <input type="hidden" name="action" value="add_card">
        <div class="modal-header">
          <h5 class="modal-title" id="modalNuovaCartaLabel">Aggiungi nuova carta di credito</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <label for="circuito" class="form-label">Circuito della carta</label>
            <select id="circuito" name="circuito" class="form-select" required>
              <option value="">Seleziona</option>
              <option value="Visa">Visa</option>
              <option value="MasterCard">MasterCard</option>
              <option value="American Express">American Express</option>
              <option value="Maestro">Maestro</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="numeroCarta" class="form-label">Numero carta</label>
            <input type="text" id="numeroCarta" name="numero" class="form-control" placeholder="1234 5678 9012 3456" required pattern="\d{16}">
          </div>
          <div class="mb-3">
            <label for="scadenza" class="form-label">Data scadenza</label>
            <input type="date" id="scadenza" name="scadenza" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="cvvNuova" class="form-label">CVV</label>
            <input type="password" id="cvvNuova" name="cvv" class="form-control" placeholder="***" required pattern="\d{3,4}">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Chiudi</button>
          <button type="submit" class="btn btn-success">Aggiungi carta</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
