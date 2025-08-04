<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8">
  <title><?= $pageTitle ?? 'Deja-brew' ?></title>
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
      position: relative;
    }
    .upload-box input[type="file"] {
      display: none;
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

<!-- Contenuto della pagina -->
<div class="container form-section mt-4">
  <?php include($pageContent); ?>
</div>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
