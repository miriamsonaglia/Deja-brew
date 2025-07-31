<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title>Aggiungi Prodotto - Deja-brew</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">

  <style>
    body {
      background-color: #F2EFEA;
    }
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
    }
    .form-section {
      margin-top: 2rem;
    }
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
    .form-check-label {
      color: #7C2E2E;
    }
    h2, label, .navbar-brand {
      color: #594431;
    }
  </style>
</head>
<body>

<!-- Navbar -->
<nav class="navbar bg-light border-bottom">
  <div class="container-fluid justify-content-between align-items-center">
    <button class="btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu">
      <span class="navbar-toggler-icon"></span>
    </button>
    <span class="navbar-brand mx-auto fw-bold">Deja-brew</span>
    <div class="d-flex align-items-center">
      <form class="d-flex me-3" role="search">
        <input class="form-control" type="search" placeholder="Cerca..." aria-label="Search">
      </form>
      <button class="btn btn-outline-secondary" type="button">
        <i class="bi bi-cart-fill"></i>
      </button>
    </div>
  </div>
</nav>

<!-- Sidebar -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
  <div class="offcanvas-header">
    <h5 class="offcanvas-title">Menu</h5>
    <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Chiudi"></button>
  </div>
  <div class="offcanvas-body">
    <ul class="list-unstyled">
      <li><a href="#" class="nav-link">Notifiche</a></li>
      <li><a href="#" class="nav-link">Ordini</a></li>
      <li><a href="#" class="nav-link">Impostazioni</a></li>
    </ul>
  </div>
</div>

<!-- Form Aggiungi Prodotto -->
<div class="container form-section">
  <h2 class="mb-4 text-center fw-bold">Aggiungi un nuovo prodotto</h2>
  <form method="POST" enctype="multipart/form-data">
    <div class="row g-4">
      <!-- Sezione Immagini -->
      <div class="col-md-6">
        <label class="form-label fw-semibold">Foto del prodotto</label>
        <div class="upload-box mb-3">
          <span>Trascina qui le immagini o clicca per selezionarle</span>
          <input type="file" multiple accept="image/*" class="form-control d-none" id="imageUpload">
        </div>

        <div id="productCarousel" class="carousel slide" data-bs-ride="carousel">
          <div class="carousel-inner rounded">
            <div class="carousel-item active">
              <img src="https://via.placeholder.com/500x300" class="d-block w-100" alt="...">
            </div>
            <div class="carousel-item">
              <img src="https://via.placeholder.com/500x300?text=2" class="d-block w-100" alt="...">
            </div>
          </div>
          <button class="carousel-control-prev" type="button" data-bs-target="#productCarousel" data-bs-slide="prev">
            <span class="carousel-control-prev-icon"></span>
          </button>
          <button class="carousel-control-next" type="button" data-bs-target="#productCarousel" data-bs-slide="next">
            <span class="carousel-control-next-icon"></span>
          </button>
        </div>
      </div>

      <!-- Sezione Dettagli Prodotto -->
      <div class="col-md-6">
        <div class="mb-3">
          <label for="productName" class="form-label fw-semibold">Nome prodotto</label>
          <input type="text" class="form-control" id="productName" name="nome">
        </div>

        <div class="mb-3">
          <label for="productPrice" class="form-label fw-semibold">Prezzo (€)</label>
          <input type="number" class="form-control" id="productPrice" name="prezzo" step="0.10">
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Marca</label><br>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="lavazza" name="marca[]" value="Lavazza">
            <label class="form-check-label" for="lavazza">Lavazza</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="illy" name="marca[]" value="Illy">
            <label class="form-check-label" for="illy">Illy</label>
          </div>
        </div>

        <div class="mb-3">
          <label class="form-label fw-semibold">Intensità</label><br>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="intensita1" name="intensita[]" value="Leggero">
            <label class="form-check-label" for="intensita1">Leggero</label>
          </div>
          <div class="form-check form-check-inline">
            <input class="form-check-input" type="checkbox" id="intensita2" name="intensita[]" value="Forte">
            <label class="form-check-label" for="intensita2">Forte</label>
          </div>
        </div>

        <div class="mb-3">
          <label for="descrizione" class="form-label fw-semibold">Descrizione</label>
          <textarea class="form-control" id="descrizione" name="descrizione" rows="5"></textarea>
        </div>

        <button type="submit" class="btn btn-info w-100 fw-semibold">Aggiungi prodotto</button>
      </div>
    </div>
  </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
