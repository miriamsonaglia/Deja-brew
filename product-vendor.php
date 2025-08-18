<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Models/Prodotto.php';
require_once __DIR__ . '/Models/Recensione.php';
require_once __DIR__ . '/Models/UtenteVenditore.php';
require_once __DIR__ . '/Models/Utente.php';

use App\Models\Prodotto;
use App\Models\Recensione;
use App\Models\UtenteVenditore;

// ---------------------------------------------------------------------------------------------
// VERSIONE ELOQUENT (quando il DB sarà popolato)
// ---------------------------------------------------------------------------------------------
// $id = $_GET['id'] ?? 1;
// $prodotto = Prodotto::find($id);
// $immagini = [$prodotto->fotografia];
// $mediaRecensioni = Recensione::where('id_prodotto', $id)->avg('stelle') ?? 0;
// $venditore = UtenteVenditore::with('user')->find($prodotto->id_venditore);

// ---------------------------------------------------------------------------------------------
// MOCK (per testare ora senza DB)
// ---------------------------------------------------------------------------------------------
$prodotto = (object) [
    'id'          => 1,
    'nome'        => 'Caffè Arabica 100%',
    'prezzo'      => 12.50,
    'peso'        => 250,
    'provenienza' => 'Colombia',
    'tipo'        => 'Grani',
    'intensita'   => 7,
    'categoria'   => 'Espresso',
    'aroma'       => 'Cioccolato',
    'descrizione' => 'Un caffè dal gusto pieno e avvolgente, con note di cacao e frutta secca.',
    'id_venditore'=> 42
];
$immagini = [
    "https://upload.wikimedia.org/wikipedia/commons/4/45/A_small_cup_of_coffee.JPG",
    "https://upload.wikimedia.org/wikipedia/commons/c/c5/Roasted_coffee_beans.jpg",
    "https://upload.wikimedia.org/wikipedia/commons/d/d7/Stacked_coffee_cans.jpg"
];
$mediaRecensioni = 3.7;

// MOCK venditore
$venditore = (object) [
    'id'   => 42,
    'user' => (object) [
        'nome'     => 'Carlo',
        'cognome'  => 'Latazza',
        'username' => 'carlolatazza'
    ]
];

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
</head>
<body>

<?php include __DIR__ . '/reusables/navbars/empty-navbar.php'; ?>
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

      <!-- Nome venditore -->
      <div class="mb-1">
        <a href="vendor-profile.php?id=<?php echo $venditore->id; ?>" class="text-decoration-none">  <!-- TODO: CAMBIARE CON NOME PAGINA PROFILO VENDITORE -->
          <span class="fw-semibold text-primary">
            <?php echo $venditore->user->nome . ' ' . $venditore->user->cognome; ?>
          </span>
        </a>
      </div>

      <!-- Stelle recensioni -->
      <div class="mb-2">
        <?php echo renderStars($mediaRecensioni); ?>
        <small class="text-muted ms-2">(<?php echo number_format($mediaRecensioni, 1); ?> / 5)</small>
      </div>

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

      <button id="btnCondividi" class="btn btn-secondary btn-lg mt-3 ms-2">
        <i class="bi bi-link-45deg"></i> Condividi
      </button>
    </div>
  </div>
</div>

<script>
  const btnCondividi = document.getElementById('btnCondividi');
  btnCondividi.addEventListener('click', () => {
    navigator.clipboard.writeText(window.location.href).then(() => {
      alert('Link copiato negli appunti!');
    });
  });
</script>

</body>
</html>
