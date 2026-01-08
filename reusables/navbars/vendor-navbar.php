<!-- Navbar -->
<nav class="navbar bg-light border-bottom">
    <div class="container-fluid justify-content-between align-items-center">
        <!-- Pulsante hamburger per aprire la sidebar -->
        <button class="btn navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#sidebarMenu" aria-controls="sidebarMenu" aria-label="Apri menu">
            <span class="navbar-toggler-icon"></span>
        </button>

        <!-- Logo/Brand centrato -->
        <span href='./home.php' class="navbar-brand mx-auto fw-bold fs-3">Deja-brew</span>
        <!-- Barra di ricerca e carrello -->
    <div class="d-flex align-items-center">
        <!-- Carrello -->
        <div class="cart-container">
            <a href="./add-product.php">
                <button class="position-relative">
                    <i class="bi bi-plus-lg fs-4"></i> Nuovo prodotto
                </button>
            </a>
        </div>
    </div>
    </div>

</nav>

<!-- Sidebar (Offcanvas) -->
<div class="offcanvas offcanvas-start" tabindex="-1" id="sidebarMenu" aria-labelledby="sidebarMenuLabel">
    <div class="offcanvas-header border-bottom">
        <h5 class="offcanvas-title fw-bold" id="sidebarMenuLabel">
            <i class="bi bi-list me-2"></i>Menu
        </h5>
        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Chiudi"></button>
    </div>
    <div class="offcanvas-body">
        <ul class="list-unstyled">
            <li>
                <a href="./notifications.php" class="nav-link">
                    <i class="bi bi-bell me-2"></i>Notifiche
                    <span class="badge bg-danger ms-auto">3</span>
                </a>
            </li>
            <li>
                <a href="orders-seller.php" class="nav-link" onclick="showOrders()">
                    <i class="bi bi-bag me-2"></i>Ordini
                </a>
            </li>
            <li>
                <a href="./profile-settings.php" class="nav-link" onclick="showSettings()">
                    <i class="bi bi-gear me-2"></i>Impostazioni Profilo
                </a>
            </li>
            <li><hr class="my-3"></li>
            
            <li>
                <a href="./logout.php" class="nav-link text-danger" onclick="logout()">
                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                </a>
            </li>
        </ul>
    </div>
</div>