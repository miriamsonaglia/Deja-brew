<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Models/Prodotto.php';

use App\Models\Prodotto;


// VERSIONE ELOQUENT (quando il DB sarà popolato)

// $id = $_GET['id'] ?? 1;
// $prodotto = Prodotto::find($id);
// $immagini = [$prodotto->fotografia]; // qui ci metterai tutte le immagini collegate

// ---------------------------------------------------------------------------------------------
// MOCK (prodotto fasullo)

$prodotto = (object) [
    'nome'        => 'Caffè Arabica 100%',
    'prezzo'      => 12.50,
    'peso'        => 250,
    'provenienza' => 'Colombia',
    'tipo'        => 'Grani',
    'intensita'   => 7,
    'categoria'   => 'Espresso',
    'aroma'       => 'Cioccolato',
    'descrizione' => 'Un caffè dal gusto pieno e avvolgente, con note di cacao e frutta secca.',
];
$immagini = [
    "https://upload.wikimedia.org/wikipedia/commons/4/45/A_small_cup_of_coffee.JPG",
    "https://upload.wikimedia.org/wikipedia/commons/c/c5/Roasted_coffee_beans.jpg",
    "https://upload.wikimedia.org/wikipedia/commons/d/d7/Stacked_coffee_cans.jpg"
];
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
</head>
<body>

<?php include __DIR__ . '/reusables/navbars/buyer-navbar.php'; ?>
<?php include __DIR__ . '/reusables/sidebar.php'; ?>

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
      <h2 class="fw-bold"><?php echo $prodotto->nome; ?></h2>
      <p class="text-muted">
        <?php echo $prodotto->categoria; ?> • <?php echo $prodotto->tipo; ?>
      </p>
      <h3 class="text-success fw-bold mb-3">
        <?php echo number_format($prodotto->prezzo, 2); ?> €
      </h3>

      <p><strong>Peso:</strong> <?php echo $prodotto->peso; ?> g</p>
      <p><strong>Provenienza:</strong> <?php echo $prodotto->provenienza; ?></p>
      <p><strong>Intensità:</strong> <?php echo $prodotto->intensita; ?>/10</p>
      <p><strong>Aroma:</strong> <?php echo $prodotto->aroma; ?></p>

      <p class="mt-3"><?php echo $prodotto->descrizione; ?></p>

      <button class="btn btn-info btn-lg mt-3">
        <i class="bi bi-cart-plus"></i> Aggiungi al carrello
      </button>
    </div>
  </div>
</div>

<script src="./dist/custom/js/sidebar-manager.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
