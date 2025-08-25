<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php'; // Connessione Eloquent
require_once __DIR__ . '/Models/Prodotto.php';

use App\Models\Prodotto;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prodotto = new Prodotto();

    $prodotto->nome         = $_POST['nome'] ?? null;
    $prodotto->prezzo       = $_POST['prezzo'] ?? null;
    $prodotto->peso         = $_POST['peso'] ?? null;
    $prodotto->provenienza  = $_POST['provenienza'] ?? null;
    $prodotto->tipo         = $_POST['tipo'] ?? null;
    $prodotto->intensita    = $_POST['intensita'] ?? null;
    $prodotto->categoria_id = $_POST['categoria_id'] ?? null;
    $prodotto->aroma_id     = $_POST['aroma_id'] ?? null;
    $prodotto->descrizione  = $_POST['descrizione'] ?? null;
    $prodotto->id_venditore = 1; // TODO: cambia con ID dinamico se uso sessioni!!!!!!!

    // Salvataggio temporaneo
    $prodotto->save();

    // Gestione immagini
    if (!empty($_FILES['immagini']['name'][0])) {
        $uploadDir = __DIR__ . '/uploads/prodotti/';
        if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);

        foreach ($_FILES['immagini']['tmp_name'] as $key => $tmpName) {
            if ($_FILES['immagini']['error'][$key] === UPLOAD_ERR_OK) {
                $originalName = basename($_FILES['immagini']['name'][$key]);
                $filePath = 'uploads/prodotti/' . time() . '_' . $originalName;
                move_uploaded_file($tmpName, __DIR__ . '/' . $filePath);

                // Salva solo la prima nel campo "fotografia"
                if ($key === 0) {
                    $prodotto->fotografia = $filePath;
                    $prodotto->save();
                }
            }
        }
    }

    header('Location: success.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Aggiungi Prodotto - Deja-brew</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <link href="/dist/custom/css/style.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body>

<?php include __DIR__ . '/reusables/navbars/vendor-navbar.php'; ?>

<div class="container form-section">
  <h2 class="mb-4 text-center fw-bold">Aggiungi un nuovo prodotto</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="row g-4">

      <div class="col-md-6">
        <label class="form-label fw-semibold">Foto del prodotto</label>
        <div class="upload-box mb-3">
          <span>Trascina qui le immagini o clicca per selezionarle</span>
          <input type="file" class="image-upload-input" name="immagini[]" multiple accept="image/*">
        </div>

        <div class="carousel slide product-carousel" data-bs-ride="carousel">
          <div class="carousel-inner rounded">
            <div class="carousel-item active">
              <img src="https://via.placeholder.com/500x300" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
              <img src="https://via.placeholder.com/500x300?text=2" class="d-block w-100" alt="...">
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target=".product-carousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target=".product-carousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
      </div>

      <div class="col-md-6">
        <div class="mb-3">
          <label class="form-label fw-semibold">Nome prodotto</label>
          <input type="text" class="form-control product-name" name="nome" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Prezzo (€)</label>
          <input type="number" class="form-control product-price" name="prezzo" step="0.10" required>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Peso (g)</label>
          <input type="number" class="form-control" name="peso" step="0.001">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Provenienza</label>
          <input type="text" class="form-control" name="provenienza">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Tipo</label>
          <select class="form-select" name="tipo">
            <option value="">Seleziona tipo</option>
            <option value="Capsule">Capsule</option>
            <option value="Cialde">Cialde</option>
            <option value="Grani">Grani</option>
            <option value="Macinato">Macinato</option>
          </select>
        </div>

        <div class="mb-3">
          <label for="intensita" class="form-label fw-semibold">Intensità: <span id="valore-intensita">5</span></label>
          <input type="range" class="form-range" name="intensita" id="intensita" min="1" max="10" value="5">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Categoria</label>
          <select class="form-select" name="categoria_id">
            <option value="">Seleziona categoria</option>
            <option value="1">Espresso</option>
            <option value="2">Decaffeinato</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Aroma</label>
          <select class="form-select" name="aroma_id">
            <option value="">Seleziona aroma</option>
            <option value="1">Cioccolato</option>
            <option value="2">Fruttato</option>
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Descrizione</label>
          <textarea class="form-control product-description" name="descrizione" rows="5" required></textarea>
        </div>

        <button type="submit" class="btn btn-info w-100 fw-semibold">Aggiungi prodotto</button>
      </div>
    </div>
  </form>
</div>

<script src='./dist/custom/js/sidebar-manager.js'></script>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const uploadBox = document.querySelector('.upload-box');
    const imageInput = document.querySelector('.image-upload-input');
    const slider = document.getElementById('intensita');
    const sliderValue = document.getElementById('valore-intensita');

    if (uploadBox && imageInput) {
      uploadBox.addEventListener('click', () => imageInput.click());
    }

    if (slider && sliderValue) {
      slider.addEventListener('input', () => {
        sliderValue.textContent = slider.value;
      });
    }
  });
</script>

</body>
</html>
