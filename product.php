<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Models/Prodotto.php';
require_once __DIR__ . '/Models/Recensione.php';
require_once __DIR__ . '/Models/UtenteVenditore.php';
require_once __DIR__ . '/Models/Utente.php';
require_once __DIR__ . '/role.php';
require_once __DIR__ . '/Models/Categoria.php';
require_once __DIR__ . '/Models/Aroma.php';
require_once __DIR__ . '/utilities.php';

use App\Models\Prodotto;
use App\Models\Recensione;
use App\Models\UtenteVenditore;

session_start();
$userRole = $_SESSION['UserRole'] ?? Role::GUEST->value;


 $id = $_GET['id'] ?? 1;
 $prodotto = Prodotto::find($id);
 $immagini = [empty($prodotto->fotografia) ? './images/products/Standard_Blend.png' : './uploads/prodotti/' .$prodotto->fotografia];
 $mediaRecensioni = Recensione::where('id_prodotto', $id)->avg('stelle') ?? 0;
 $venditore = UtenteVenditore::with('user')->find($prodotto->id_venditore);
 $recensioni = Recensione::where('id_prodotto', $id)
     ->with('utente')
     ->get();

// Funzione helper per generare stelline
function renderStars($media) {
    $output = '';
    for ($i = 1; $i <= 5; $i++) {
        if ($media >= $i) {
            $output .= '<i class="bi bi-star-fill text-warning"></i> ';
        } elseif ($media >= $i - 0.5) {
            $output .= '<i class="bi bi-star-half text-warning"></i> ';
        } else {
            $output .= '<i class="bi bi-star text-warning"></i> ';
        }
    }
    return $output;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title><?php echo $prodotto->nome; ?> - Deja-brew</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="/dist/custom/css/new-style.css" rel="stylesheet">

  <style>
    /* Custom styles to match home page functionality */
    .product-card {
      border: 1px solid #dee2e6;
      border-radius: 8px;
      padding: 20px;
      background: white;
      box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }

    .quantity-input {
      width: 80px;
      text-align: center;
      border: 1px solid #ced4da;
      border-radius: 4px;
      padding: 6px;
    }

    .cart-button, .wish-button {
      padding: 8px 16px;
      border-radius: 4px;
      border: 1px solid;
      cursor: pointer;
      font-size: 14px;
      transition: all 0.3s ease;
    }

    .cart-button {
      background-color: #28a745;
      color: white;
      border-color: #28a745;
    }

    .cart-button:hover {
      background-color: #218838;
      border-color: #1e7e34;
    }

    .wish-button {
      background-color: transparent;
      color: #ff4444;
      border-color: #ff4444;
      padding: 8px 12px;
      margin-left: 8px;
    }

    .wish-button:hover {
      background-color: rgba(255, 68, 68, 0.1);
    }

    .product-actions {
      display: flex;
      gap: 10px;
      margin-top: 15px;
    }

    .quantity-container {
      display: flex;
      align-items: center;
      gap: 5px;
    }
    img {
      max-height: 70vh;
      object-fit: contain;
    }
  </style>
</head>
<body>

<?php
    switch($userRole) {
        case Role::GUEST->value:
            include('./reusables/navbars/empty-navbar.php');
            break;
        case Role::BUYER->value:
            include('./reusables/navbars/buyer-navbar.php');
            break;
        case Role::VENDOR->value:
            include('./reusables/navbars/vendor-navbar.php');
            break;
        default:
            break;
    }
?>

<main class="container my-5">
  <div class="row g-4">
    <!-- Carosello immagini -->
    <div class="col-md-6">
      <div id="carouselProdotto" class="carousel slide shadow rounded" data-bs-ride="carousel">
        <div class="carousel-inner">
          <?php foreach ($immagini as $index => $img): ?>
            <div class="carousel-item <?php echo $index === 0 ? 'active' : ''; ?>">
              <img src="<?php echo $img; ?>" class="d-block w-100 rounded" alt="Immagine prodotto <?php echo $index+1; ?>">
            </div>
          <?php endforeach; ?>
        </div>
        <!--
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselProdotto" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselProdotto" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
          -->
      </div>
    </div>

    <!-- Dettagli prodotto -->
    <div class="col-md-6">
      <div class="product-card">
        <h1 class="fw-bold product-name"><?php echo $prodotto->nome; ?></h1>

        <!-- Nome venditore -->
        <div class="mb-1">
          <a href="vendor-profile.php?id=<?php echo $venditore->id_utente; ?>" class="text-decoration-none">
            <span class="fw-semibold text-primary">
              <?php echo $venditore->user->nome . ' ' . $venditore->user->cognome; ?>
            </span>
          </a>
        </div>

        <!-- Stelle recensioni -->
        <div class="mb-2 d-flex align-items-center gap-2">
          <div>
            <?php echo renderStars($mediaRecensioni); ?>
            <small class="text-muted ms-2">(<?php echo number_format($mediaRecensioni, 1); ?> / 5)</small>
          </div>
          <?php if(isset($userRole) && ($userRole == Role::BUYER->value)): ?>
            <a href="#" class="ms-3 text-decoration-none text-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#modalRecensione" aria-label="Aggiungi una recensione">
              <i class="bi bi-pencil-square" aria-hidden="true"></i> Aggiungi una recensione
            </a>
          <?php endif; ?>
          <?php if(isset($userRole) && ($userRole == Role::VENDOR->value) && $venditore->id_utente == $_SESSION['LoggedUser']['id']): ?>
            <a href="#" class="ms-3 text-decoration-none text-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#modalModificaArticolo" aria-label="Modifica articolo">
              <i class="bi bi-pencil-square" aria-hidden="true"></i> Modifica articolo
            </a>
          <?php endif; ?>
        </div>

        <p class="text-muted">
          <?php echo $prodotto->categoria->descrizione; ?> • <?php echo $prodotto->tipo; ?>
        </p>
        <h2 class="text-success fw-bold mb-3 product-price">
          <?php echo number_format($prodotto->prezzo, 2); ?> €
        </h2>

        <p><strong>Peso:</strong> <?php echo $prodotto->peso; ?> g</p>
        <p><strong>Provenienza:</strong> <?php echo $prodotto->provenienza; ?></p>
        <p><strong>Intensità:</strong> <?php echo $prodotto->intensita; ?>/10</p>
        <p><strong>Aroma:</strong> <?= $prodotto->aroma->gusto ?? 'N/A' ?></p>

        <p class="mt-3"><?php echo $prodotto->descrizione; ?></p>

        <?php if(isset($userRole) && ($userRole == Role::BUYER->value)): ?>
          <!-- Actions compatible with JavaScript -->
          <div class="product-actions">
            <span>
              <div class="quantity-container">
                <label for="quantity-<?php echo $prodotto->id; ?>" class="form-label">Quantità:</label>
                <input type="number"
                       step="1"
                       value="1"
                       min="1"
                       max="99"
                       class="quantity-input"
                       id="quantity-<?php echo $prodotto->id; ?>"
                       data-product-id="<?php echo $prodotto->id; ?>">
              </div>
            </span>

            <span>
              <div>
                <button class="cart-button"
                        data-product-id="<?php echo $prodotto->id; ?>"
                        data-product-name="<?php echo htmlspecialchars($prodotto->nome); ?>"
                        data-product-price="<?php echo $prodotto->prezzo; ?>">
                  <i class="bi bi-cart-plus"></i> Aggiungi al carrello
                </button>
              </div>
            </span>

            <span>
              <button class="wish-button"
                      data-product-id="<?php echo $prodotto->id; ?>"
                      aria-label="<?php echo wished($prodotto->id, $_SESSION['LoggedUser']['id']) ? 'Rimuovi dalla wishlist' : 'Aggiungi alla wishlist'; ?>">
                <i class="bi <?php echo wished($prodotto->id, $_SESSION['LoggedUser']['id']) ? 'bi-heart-fill text-danger' : 'bi-heart'; ?>" aria-hidden="true"></i>
              </button>
            </span>
          </div>

          <!-- Buy now button -->
          <div class="mt-3">
            <button type="button" class="btn btn-warning" data-bs-toggle="modal" data-bs-target="#modalCheckout">
              <i class="bi bi-bag-check"></i> Acquista ora
            </button>
            <?php endif; ?>
            
            <!-- PULSANTI CONDIVIDI -->
              <button id="btnCondividi" class="btn btn-outline-secondary">
                <i class="bi bi-link-45deg" aria-hidden="true"></i> Condividi
              </button>
            
          </div>
      </div>
    </div>
  </div>
</main>

<!-- Sezione Recensioni -->
<div class="container my-5">
  <h3 class="fw-bold mb-4">Recensioni</h3>

  <?php foreach ($recensioni as $index => $rec): ?>
    <div class="recensione mb-3 <?php echo $index >= 2 ? 'd-none extra-recensione' : ''; ?>">
      <p class="mb-1 fw-semibold">
        <?php echo $rec->utente->nome . ' ' . $rec->utente->cognome; ?>
      </p>
      <div class="mb-1">
        <?php echo renderStars($rec->stelle); ?>
      </div>
      <p class="text-muted"><?php echo $rec->testo; ?></p>
      <hr>
    </div>
  <?php endforeach; ?>

  <?php if (count($recensioni) > 2): ?>
    <button id="btnToggleRecensioni" class="btn btn-info">Vedi altre</button>
  <?php endif; ?>
</div>

<!-- MODAL CHECKOUT SINGOLO PRODOTTO -->
<div class="modal fade" id="modalCheckout" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Completa l'acquisto</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form method="POST" action="checkout.php" class="modal-form">
        <div class="modal-body">
          <div class="mb-3">
            <h6 class="fw-bold">Prodotto</h6>
            <p class="mb-1"><?php echo $prodotto->nome; ?></p>
            <small class="text-muted"><?php echo number_format($prodotto->prezzo, 2); ?> €</small>
          </div>

          <div class="mb-3">
            <label for="modalQuantity" class="form-label">Quantità</label>
            <input type="number" 
                   id="modalQuantity" 
                   name="quantita" 
                   value="1" 
                   min="1" 
                   max="99" 
                   class="form-control">
          </div>

          <div class="mb-3 p-3 bg-light rounded">
            <h6 class="fw-bold mb-2">Totale</h6>
            <h5 class="text-success" id="modalTotal"><?php echo number_format($prodotto->prezzo, 2); ?> €</h5>
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annulla</button>
          <button type="submit" class="btn btn-success">
            <i class="bi bi-credit-card"></i> Procedi al pagamento
          </button>
        </div>
        <input type="hidden" name="id_prodotto" value="<?php echo $prodotto->id; ?>">
        <input type="hidden" name="buy_now" value="1">
      </form>
    </div>
  </div>
</div>

<!-- MODAL RECENSIONE -->
<div class="modal fade" id="modalRecensione" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form method="POST" action="save-review.php" class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Lascia una recensione</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">

        <input type="hidden" name="id_prodotto" value="<?php echo $prodotto->id; ?>">
        <input type="hidden" name="id_utente" value="<?php echo $_SESSION['user_id'] ?? 1; ?>">

        <!-- Selezione stelle -->
        <div class="mb-3">
          <label class="form-label">Valutazione</label>
          <div class="d-flex gap-1" id="starSelector">
            <?php for ($i=1; $i<=5; $i++): ?>
              <i class="bi bi-star star-icon fs-3" data-value="<?php echo $i; ?>"></i>
            <?php endfor; ?>
          </div>
          <input type="hidden" name="stelle" id="stelleInput" required>
        </div>

        <!-- Testo recensione -->
        <div class="mb-3">
          <label for="testo" class="form-label">Commento</label>
          <textarea name="testo" id="testo" rows="3" class="form-control" required></textarea>
        </div>

      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-warning">Invia</button>
      </div>
    </form>
  </div>
</div>

<div class="modal fade" id="modalModificaArticolo" tabindex="-1" aria-labelledby="modalModificaArticoloLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="formModificaArticolo" action="actions/update_card.php" method="POST">
        <div class="modal-header">
          <h5 class="modal-title" id="modalModificaArticoloLabel">Modifica Articolo</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Chiudi"></button>
        </div>
        <input type="hidden" id="modal-card_id" name="card_id">
        <div class="modal-body">
          <div class="mb-3">
            <label for="modal-circuito_pagamento" class="form-label">Circuito della carta</label>
            <select id="modal-circuito_pagamento" name="circuito_pagamento" class="form-select" required>
              <option value="">Seleziona</option>
              <option value="Visa">Visa</option>
              <option value="MasterCard">MasterCard</option>
              <option value="American Express">American Express</option>
              <option value="Maestro">Maestro</option>
            </select>
          </div>
          <div class="mb-3">
            <label for="modal-codice_carta" class="form-label">Numero carta</label>
            <input type="text" id="modal-codice_carta" name="codice_carta" class="form-control" placeholder="1234 5678 9012 3456" required pattern="\d{16}">
          </div>
          <div class="mb-3">
            <label for="modal-scadenza" class="form-label">Data scadenza</label>
            <input type="month" id="modal-scadenza" name="scadenza" class="form-control" required>
          </div>
          <div class="mb-3">
            <label for="modal-cvv" class="form-label">CVV</label>
            <input type="password" id="modal-cvv" name="cvv_carta" class="form-control" placeholder="***" required pattern="\d{3,4}">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-outline-danger" data-bs-dismiss="modal">Chiudi</button>
          <button type="submit" formaction="actions/update_card.php" class="btn btn-success">Modifica carta</button>
          <button type="submit" formaction="actions/delete_card.php" class="btn btn-danger" onclick="return confirm('Sei sicuro di voler eliminare questa carta?')">Elimina carta</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- JavaScript imports -->
<script src="./dist/custom/js/sidebar-manager.js"></script>
<script src="./dist/bootstrap5/js/bootstrap.min.js"></script>

<?php if(isset($userRole) && ($userRole === Role::BUYER->value)): ?>
  <script src="./dist/custom/js/wishlist-manager.js"></script>
  <script src="./dist/custom/js/cart-manager.js"></script>
  <script src="./dist/custom/js/input-validation.js"></script>
<?php endif; ?>

<script>
// Share functionality
document.getElementById('btnCondividi').addEventListener('click', () => {
  navigator.clipboard.writeText(window.location.href).then(() => {
    alert('Link copiato negli appunti!');
  });
});

// Reviews toggle functionality
const btnToggle = document.getElementById('btnToggleRecensioni');
if (btnToggle) {
  btnToggle.addEventListener('click', () => {
    const extra = document.querySelectorAll('.extra-recensione');
    const isHidden = extra[0].classList.contains('d-none');

    if (isHidden) {
      extra.forEach(el => el.classList.remove('d-none'));
      btnToggle.textContent = 'Nascondi';
    } else {
      extra.forEach(el => el.classList.add('d-none'));
      btnToggle.textContent = 'Vedi altre';
      window.scrollTo({ top: btnToggle.offsetTop - 200, behavior: 'smooth' });
    }
  });
}

// Star rating functionality
const starIcons = document.querySelectorAll('.star-icon');
const stelleInput = document.getElementById('stelleInput');

starIcons.forEach(icon => {
  icon.addEventListener('click', () => {
    const value = icon.getAttribute('data-value');
    stelleInput.value = value;

    starIcons.forEach(i => {
      if (i.getAttribute('data-value') <= value) {
        i.classList.remove('bi-star');
        i.classList.add('bi-star-fill', 'text-warning');
      } else {
        i.classList.add('bi-star');
        i.classList.remove('bi-star-fill', 'text-warning');
      }
    });
  });
});

// Sync quantity for buy now button
<?php if(isset($userRole) && ($userRole === Role::BUYER->value)): ?>
document.querySelector('.quantity-input').addEventListener('change', function() {
  // Aggiorna il totale nel modale
  const modalQuantity = document.getElementById('modalQuantity');
  if (modalQuantity) {
    modalQuantity.value = this.value;
    updateModalTotal();
  }
});

// Aggiorna il totale quando cambia la quantità nel modale
const modalQuantity = document.getElementById('modalQuantity');
if (modalQuantity) {
  modalQuantity.addEventListener('change', updateModalTotal);
}

function updateModalTotal() {
  const quantity = parseInt(document.getElementById('modalQuantity').value) || 1;
  const pricePerItem = <?php echo $prodotto->prezzo; ?>;
  const total = (quantity * pricePerItem).toFixed(2);
  document.getElementById('modalTotal').textContent = total + ' €';
}

// Sincronizza il campo nascosto quando il form viene inviato
const checkoutForm = document.querySelector('.modal-form');
if (checkoutForm) {
  checkoutForm.addEventListener('submit', function() {
    const quantityField = this.querySelector('input[name="quantita"]');
    quantityField.value = document.getElementById('modalQuantity').value;
  });
}

// Initialize cart count
document.addEventListener('DOMContentLoaded', function() {
  updateCartCount();
});
<?php endif; ?>
</script>

</body>
</html>