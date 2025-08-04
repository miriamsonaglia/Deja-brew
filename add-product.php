<?php require_once 'reusables/layout.php'; ?>
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Aggiungi Prodotto - Deja-brew</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body { background-color: #F2EFEA; }
    .upload-box {
      background-color: #f8f9fa;
      border: 2px dashed #594431;
      border-radius: 8px;
      height: 300px;
      display: flex;
      align-items: center;
      justify-content: center;
      color: #594431;
      font-weight: 500;
      cursor: pointer;
      position: relative;
    }
    .upload-box input[type="file"] { display: none; }
    .form-section { margin-top: 2rem; }
    .carousel-inner img {
      object-fit: cover;
      height: 300px;
    }
    .btn-info {
      background-color: #7C2E2E;
      border: none;
      color: #fff;
    }
    .btn-info:hover {
      background-color: #3E2C23;
    }
    .form-check-label { color: #7C2E2E; }
    h2, label, .navbar-brand { color: #594431; }
  </style>
</head>
<body>

<div class="container form-section">
  <h2 class="mb-4 text-center fw-bold">Aggiungi un nuovo prodotto</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="row g-4">

      <!-- Colonna sinistra: Immagini -->
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

      <!-- Colonna destra: Dati prodotto -->
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
          <label class="form-label fw-semibold">Marca</label><br>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="marca[]" value="Lavazza">
            <label class="form-check-label">Lavazza</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" name="marca[]" value="Illy">
            <label class="form-check-label">Illy</label>
          </div>
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
            <!-- ... -->
          </select>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Aroma</label>
          <select class="form-select" name="aroma_id">
            <option value="">Seleziona aroma</option>
            <option value="1">Cioccolato</option>
            <option value="2">Fruttato</option>
            <!-- ... -->
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

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const uploadBox = document.querySelector('.upload-box');
    const imageInput = document.querySelector('.image-upload-input');
    const nameInput = document.querySelector('.product-name');
    const priceInput = document.querySelector('.product-price');
    const descInput = document.querySelector('.product-description');
    const form = document.querySelector('form');

    uploadBox.addEventListener('click', () => imageInput.click());

    form.addEventListener('submit', (e) => {
      if (!nameInput.value.trim() || !priceInput.value.trim() || !descInput.value.trim()) {
        e.preventDefault();
        alert('Per favore, compila tutti i campi richiesti.');
      }
    });
  });
  
  const slider = document.getElementById('intensita');
  const valore = document.getElementById('valore-intensita');

  if (slider && valore) {
    slider.addEventListener('input', () => {
      valore.textContent = slider.value;
    });
  }
</script>

</body>
</html>
