<?php
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/bootstrap.php';
require_once __DIR__ . '/Models/Prodotto.php';
require_once __DIR__ . '/Models/Categoria.php';
require_once __DIR__ . '/Models/Aroma.php';
require_once __DIR__ . '/role.php';

require_once __DIR__ . '/Models/UtenteVenditore.php';

use App\Models\Prodotto;
use App\Models\Categoria;
use App\Models\Aroma;
use App\Models\UtenteVenditore;

session_start();

// Verifica se l'utente è un venditore
if (!isset($_SESSION['LoggedUser']) || $_SESSION['UserRole'] !== Role::VENDOR->value) {
    header('Location: home.php');
    exit;
}

// Carica categorie e aromi dal database
$categorie = Categoria::all();
$aromi = Aroma::all();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $prodotto = new Prodotto();
    $prodotto->nome         = $_POST['nome'] ?? null;
    $prodotto->prezzo       = floatval($_POST['prezzo'] ?? 0);
    $prodotto->peso         = floatval($_POST['peso'] ?? 0);
    $prodotto->provenienza  = $_POST['provenienza'] ?? null;
    $prodotto->tipo         = $_POST['tipo'] ?? null;
    $prodotto->intensita    = intval($_POST['intensita'] ?? 5);
    $prodotto->categoria_id = intval($_POST['categoria_id'] ?? 0);
    $prodotto->aroma_id     = intval($_POST['aroma_id'] ?? 0);
    
    // Ottieni l'ID di utenteVenditore dell'utente loggato
    $utenteVenditore = UtenteVenditore::where('id_utente', $_SESSION['LoggedUser']['id'])->first();
    if (!$utenteVenditore) {
        $_SESSION['errors'] = [
            "Errore: il tuo account non è registrato come venditore.",
            "Contatta l'amministratore del sito."
        ];
        header('Location: add-product.php');
        exit;
    }
    
    $prodotto->id_venditore = $utenteVenditore->id;
    
    // Validazione
    $errors = [];
    if (empty($prodotto->nome)) $errors[] = "Il nome è obbligatorio.";
    if ($prodotto->prezzo <= 0) $errors[] = "Il prezzo è obbligatorio e deve essere maggiore di 0.";
    if ($prodotto->categoria_id <= 0) $errors[] = "La categoria è obbligatoria.";
    if ($prodotto->aroma_id <= 0) $errors[] = "L'aroma è obbligatorio.";
    
    // Verifica che la categoria esista
    if ($prodotto->categoria_id > 0 && !Categoria::find($prodotto->categoria_id)) {
        $errors[] = "La categoria selezionata non esiste nel database.";
    }
    
    // Verifica che l'aroma esista
    if ($prodotto->aroma_id > 0 && !Aroma::find($prodotto->aroma_id)) {
        $errors[] = "L'aroma selezionato non esiste nel database.";
    }
    
    if (!empty($errors)) {
        $_SESSION['errors'] = $errors;
        header('Location: add-product.php');
        exit;
    }
    
    try {
        // Salva il prodotto
        $prodotto->save();
        
        // Gestione immagini
        if (!empty($_FILES['immagini']['name'][0])) {
            $uploadDir = __DIR__ . '/uploads/prodotti/';
            if (!file_exists($uploadDir)) mkdir($uploadDir, 0777, true);
            
            foreach ($_FILES['immagini']['tmp_name'] as $key => $tmpName) {
                if ($_FILES['immagini']['error'][$key] === UPLOAD_ERR_OK) {
                    $originalName = basename($_FILES['immagini']['name'][$key]);
                    $fileName = time() . '_' . preg_replace('/[^a-zA-Z0-9._-]/', '', $originalName);
                    $filePath = 'uploads/prodotti/' . $fileName;
                    
                    move_uploaded_file($tmpName, __DIR__ . '/' . $filePath);
                    
                    // Salva solo la prima immagine nel campo "fotografia"
                    if ($key === 0) {
                        $prodotto->fotografia = $fileName;
                        $prodotto->save();
                    }
                }
            }
        }
        
        $_SESSION['success'] = "Prodotto aggiunto con successo!";
        header('Location: home.php');
        exit;
    } catch (\Exception $e) {
        $_SESSION['errors'] = [
            "Errore nel salvataggio del prodotto.",
            "Dettagli: " . $e->getMessage(),
            "",
            "Per diagnosticare il problema, verifica che:",
            "- La categoria con ID {$prodotto->categoria_id} esista nel database",
            "- L'aroma con ID {$prodotto->aroma_id} esista nel database",
            "- Il tuo account sia registrato nella tabella utentevenditore",
            "",
            "Se il problema persiste, contatta l'amministratore."
        ];
        header('Location: add-product.php');
        exit;
    }
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
  <link href="/dist/custom/css/new-style.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</head>
<body>
<?php include __DIR__ . '/reusables/navbars/vendor-navbar.php'; ?>
<main class="container form-section mb-5">
  <h1 class="mb-4 text-center fw-bold">Aggiungi un nuovo prodotto</h1>
  
  <?php if (isset($_SESSION['success'])): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <?= $_SESSION['success'] ?>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['success']); ?>
  <?php endif; ?>
  
  <?php if (isset($_SESSION['errors']) && !empty($_SESSION['errors'])): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <ul class="mb-0">
        <?php foreach ($_SESSION['errors'] as $error): ?>
          <li><?= htmlspecialchars($error) ?></li>
        <?php endforeach; ?>
      </ul>
      <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
    <?php unset($_SESSION['errors']); ?>
  <?php endif; ?>
  
  <form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
      <div class="col-md-6">
        <label for="immagini" class="form-label fw-semibold">Foto del prodotto</label>
        <div id="upload-box" class="mb-3 border border-2 rounded-3 p-4 text-center border-dashed" style="cursor:pointer">
          <span>Trascina qui le immagini o clicca per selezionarle</span>
          <input type="file" id="immagini" class="image-upload-input" name="immagini[]" multiple accept="image/*" hidden>
        </div>
        <div id="productCarousel" class="carousel slide d-none" data-bs-ride="carousel">
          <div class="carousel-inner rounded" id="productCarouselInner"></div>
          <button class="carousel-control-prev d-none" id="carouselPrev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev" aria-label="Immagine precedente">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          </button>
          <button class="carousel-control-next d-none" id="carouselNext" type="button" data-bs-target="#productCarousel" data-bs-slide="next" aria-label="Immagine successiva">
            <span class="carousel-control-next-icon" aria-hidden="true"></span>
          </button>
        </div>
        <div class="text-muted small mt-2" id="noImagesHint">Nessuna immagine selezionata.</div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <label for="nome" class="form-label fw-semibold">Nome prodotto</label>
          <input type="text" id="nome" class="form-control product-name" name="nome" required>
        </div>
        <div class="mb-3">
          <label for="prezzo" class="form-label fw-semibold">Prezzo (€)</label>
          <input type="number" id="prezzo" class="form-control product-price" name="prezzo" step="0.10" required>
        </div>
        <div class="mb-3">
          <label for="peso" class="form-label fw-semibold">Peso (g)</label>
          <input type="number" id="peso" class="form-control" name="peso" step="0.001">
        </div>
        <div class="mb-3">
          <label for="provenienza" class="form-label fw-semibold">Provenienza</label>
          <input type="text" id="provenienza" class="form-control" name="provenienza">
        </div>
        <div class="mb-3">
          <label for="tipo" class="form-label fw-semibold">Tipo</label>
          <select id="tipo" class="form-select" name="tipo">
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
          <label for="categoria_id" class="form-label fw-semibold">Categoria</label>
          <select id="categoria_id" class="form-select" name="categoria_id" required>
            <option value="">Seleziona categoria</option>
            <?php foreach ($categorie as $categoria): ?>
              <option value="<?= $categoria->id ?>"><?= htmlspecialchars($categoria->descrizione) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <div class="mb-3">
          <label for="aroma_id" class="form-label fw-semibold">Aroma</label>
          <select id="aroma_id" class="form-select" name="aroma_id" required>
            <option value="">Seleziona aroma</option>
            <?php foreach ($aromi as $aroma): ?>
              <option value="<?= $aroma->id ?>"><?= htmlspecialchars($aroma->gusto) ?></option>
            <?php endforeach; ?>
          </select>
        </div>
        <button type="submit" class="btn btn-primary-custom w-100 fw-semibold">
          <i class="bi bi-plus-circle me-2"></i>Aggiungi prodotto
        </button>
      </div>
    </div>
  </form>
</main>
<script src='./dist/custom/js/sidebar-manager.js'></script>
<script>
  document.addEventListener('DOMContentLoaded', () => {
    const uploadBox = document.getElementById('upload-box');
    const imageInput = uploadBox.querySelector('input');
    const slider = document.getElementById('intensita');
    const sliderValue = document.getElementById('valore-intensita');
    const carousel = document.getElementById('productCarousel');
    const carouselInner = document.getElementById('productCarouselInner');
    const noImagesHint = document.getElementById('noImagesHint');
    const btnPrev = document.getElementById('carouselPrev');
    const btnNext = document.getElementById('carouselNext');
    
    if (uploadBox && imageInput) {
      uploadBox.addEventListener('click', () => imageInput.click());
    
      ['dragenter', 'dragover'].forEach(event => {
        uploadBox.addEventListener(event, e => {
          e.preventDefault();
          uploadBox.classList.add('dragover');
        });
      });

      ['dragleave', 'drop'].forEach(event => {
        uploadBox.addEventListener(event, e => {
          e.preventDefault();
          uploadBox.classList.remove('dragover');
        });
      });
    
    }
    if (slider && sliderValue) {
      slider.addEventListener('input', () => {
        sliderValue.textContent = slider.value;
      });
    }
    if (imageInput && carousel && carouselInner) {
      imageInput.addEventListener('change', () => {
        // Svuota il carosello
        carouselInner.innerHTML = '';
        const files = Array.from(imageInput.files || []).filter(f => f.type && f.type.startsWith('image/'));
        if (files.length === 0) {
          carousel.classList.add('d-none');
          noImagesHint.classList.remove('d-none');
          btnPrev.classList.add('d-none');
          btnNext.classList.add('d-none');
          return;
        }
        // Popola il carosello con le anteprime
        files.forEach((file, idx) => {
          if (!file) return;
          const url = URL.createObjectURL(file);
          const item = document.createElement('div');
          item.className = 'carousel-item' + (idx === 0 ? ' active' : '');
          const img = document.createElement('img');
          img.src = url;
          img.className = 'd-block w-100';
          img.alt = 'Anteprima immagine ' + (idx + 1);
          img.style.maxHeight = '400px';
          img.style.objectFit = 'contain';
          item.appendChild(img);
          carouselInner.appendChild(item);
        });
        // Mostra carosello e controlli (se > 1 immagine)
        carousel.classList.remove('d-none');
        noImagesHint.classList.add('d-none');
        if (files.length > 1) {
          btnPrev.classList.remove('d-none');
          btnNext.classList.remove('d-none');
        } else {
          btnPrev.classList.add('d-none');
          btnNext.classList.add('d-none');
        }
      });

      uploadBox.addEventListener('drop', e => {
        const file = e.dataTransfer.files[0];
        if (!file) return;

        const dataTransfer = new DataTransfer();
        dataTransfer.items.add(file);
        imageInput.files = dataTransfer.files;
        const item = document.createElement('div');
        item.className = 'carousel-item' +' active';
        const img = document.createElement('img');
        img.className = 'd-block w-100';
        img.alt = 'Anteprima immagine ';
        img.style.maxHeight = '400px';
        img.style.objectFit = 'contain';

        img.src = URL.createObjectURL(file);
        item.appendChild(img);
        carouselInner.appendChild(item);

        carousel.classList.remove('d-none');
        noImagesHint.classList.add('d-none');

      });
    }
  });
</script>
</body>
</html>
