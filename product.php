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
use App\Models\Categoria;
use App\Models\Utente;
use App\Models\Aroma;

session_start();
$userRole = $_SESSION['UserRole'] ?? Role::GUEST->value;

// ---------------------------------------------------------------------------------------------
// VERSIONE ELOQUENT (quando il DB sarà popolato)
// ---------------------------------------------------------------------------------------------
 $id = $_GET['id'] ?? 1;
 $prodotto = Prodotto::find($id);
 $immagini = [$prodotto->fotografia];
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
  <link href="/dist/custom/css/style.css" rel="stylesheet">
  
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
      align-items: center;
      gap: 10px;
      margin-top: 15px;
    }
    
    .quantity-container {
      display: flex;
      align-items: center;
      gap: 5px;
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

<div class="container my-5">
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
        <button class="carousel-control-prev" type="button" data-bs-target="#carouselProdotto" data-bs-slide="prev">
          <span class="carousel-control-prev-icon"></span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#carouselProdotto" data-bs-slide="next">
          <span class="carousel-control-next-icon"></span>
        </button>
      </div>
    </div>

    <!-- Dettagli prodotto -->
    <div class="col-md-6">
      <div class="product-card">
        <h2 class="fw-bold product-name"><?php echo $prodotto->nome; ?></h2>

        <!-- Nome venditore -->
        <div class="mb-1">
          <a href="vendor-profile.php?id=<?php echo $venditore->id; ?>" class="text-decoration-none">
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
            <a href="#" class="ms-3 text-decoration-none text-primary fw-semibold" data-bs-toggle="modal" data-bs-target="#modalRecensione">
              <i class="bi bi-pencil-square"></i> Aggiungi una recensione
            </a>
          <?php endif; ?>
        </div>

        <p class="text-muted">
          <?php echo $prodotto->categoria; ?> • <?php echo $prodotto->tipo; ?>
        </p>
        <h3 class="text-success fw-bold mb-3 product-price">
          <?php echo number_format($prodotto->prezzo, 2); ?> €
        </h3>

        <p><strong>Peso:</strong> <?php echo $prodotto->peso; ?> g</p>
        <p><strong>Provenienza:</strong> <?php echo $prodotto->provenienza; ?></p>
        <p><strong>Intensità:</strong> <?php echo $prodotto->intensita; ?>/10</p>
        <p><strong>Aroma:</strong> <?= $prodotto->aroma->gusto ?? 'N/A' ?></p>

        <p class="mt-3"><?php echo $prodotto->descrizione; ?></p>

        <?php if(isset($userRole) && ($userRole == Role::BUYER->value)): ?>
          <!-- Actions compatible with JavaScript -->
          <div class="product-actions">
            <div class="quantity-container">
              <label for="quantity-<?php echo $prodotto->id; ?>" class="form-label mb-0 me-2">Quantità:</label>
              <input type="number" 
                     step="1" 
                     value="1" 
                     min="1" 
                     max="99"
                     class="quantity-input"
                     id="quantity-<?php echo $prodotto->id; ?>"
                     data-product-id="<?php echo $prodotto->id; ?>">
            </div>
            
            <button class="cart-button" 
                    data-product-id="<?php echo $prodotto->id; ?>"
                    data-product-name="<?php echo htmlspecialchars($prodotto->nome); ?>"
                    data-product-price="<?php echo $prodotto->prezzo; ?>">
              <i class="bi bi-cart-plus"></i> Aggiungi al carrello
            </button>
            
            <button class="wish-button" 
                    data-product-id="<?php echo $prodotto->id; ?>"
                    <?php if(wished($prodotto->id, $_SESSION['LoggedUser']['id'])): ?>
                      title="Rimuovi dalla wishlist">
                      <i class="bi bi-heart-fill"></i>
                    <?php else: ?>
                      title="Aggiungi alla wishlist">
                      <i class="bi bi-heart"></i>
                    <?php endif; ?>
            </button>
          </div>

          <!-- Buy now button -->
          <div class="mt-3">
            <form method="POST" action="checkout.php" class="d-inline">
              <input type="hidden" name="id_prodotto" value="<?php echo $prodotto->id; ?>">
              <input type="hidden" id="buyNowQuantity" name="quantita" value="1">
              <button type="submit" class="btn btn-warning">
                <i class="bi bi-bag-check"></i> Acquista ora
              </button>
            </form>
          </div>
        <?php endif; ?>

        <!-- PULSANTI CONDIVIDI -->
        <div class="d-flex gap-2 mt-3">
          <button id="btnCondividi" class="btn btn-outline-secondary">
            <i class="bi bi-link-45deg"></i> Condividi
          </button>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Sezione Recensioni -->
<div class="container my-5">
  <h4 class="fw-bold mb-4">Recensioni</h4>

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

<!-- JavaScript imports -->
<script src="./dist/custom/js/sidebar-manager.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

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
  document.getElementById('buyNowQuantity').value = this.value;
});

// Initialize cart count
document.addEventListener('DOMContentLoaded', function() {
  updateCartCount();
});
<?php endif; ?>
</script>

</body>
</html>