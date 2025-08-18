<!DOCTYPE html>
<html>
    <head>
        <!-- INSERT HERE ALL CSS NECESSARY IMPORTS -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
        <link rel="stylesheet" href="./dist/custom/css/style.css">

        <?php
            // This in-page script initialize all the required php imports and populates the datasets.
            require_once __DIR__ . '/bootstrap.php';
            require_once __DIR__ . '/Models/Categoria.php';
            require_once __DIR__ . '/Models/Prodotto.php';
            require_once __DIR__ . '/role.php';
            use App\Models\Categoria;
            use App\Models\Prodotto;
            $userRole = $_SESSION['UserRole'] ?? Role::GUEST;
            // QUERY: READ ALL CATEGORIES FROM DATABASE
            $categories = Categoria::all();
            /**
             * The generated array "concatenate" its an array of associative array.
             * Every element has one Category and an array of products.
             */
        ?>
    </head>
    <body>
        <header><!-- ?? Possible header template ?? --></header>
        <?php 
            switch($userRole) {
                case Role::GUEST:
                    include('./reusables/navbars/empty-navbar.php');
                    break;
                case Role::BUYER:
                    include('./reusables/navbars/buyer-navbar.php');
                    break;
                case Role::VENDOR:
                    include('./reusables/navbars/vendor-navbar.php');
                    break;
                default:
                    break;
            }
        ?>
        <!-- Slider template -->
        <?php 
        foreach($categories as $category):
            $products = Prodotto::where('categoria_id', $category->id)->get();
        ?>
        <section>
            <h1><?php echo $category->descrizione; ?></h1><a href="http://localhost:8080/category-products.php?category=<?php echo $category->id; ?>">Vedi tutti</a>
            <button class="slider-backward"><-</button>
            <div class="slider-container">
                <ul class="slider-list">
                <?php foreach($products as $product): ?>
                    <!-- At the moment the template is very basic -->
                    <li class="slider-object"><?php echo $product->nome; ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
            <button class="slider-forward">-></button>
        </section>
        <?php endforeach;?>
        <section>
            <h1>CATEGORIA DI PROVA</h1><a href="http://localhost:8080/category-products.php?category=0">Vedi tutti</a>
            <button class="slider-backward"><</button>
            <div class="slider-container">
                <ul class="slider-list">
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                </ul>
            </div>
            <button class="slider-forward">></button>
        </section>
        <section>
            <h1>CATEGORIA DI PROVA</h1><a href="http://localhost:8080/category-products.php?category=0">Vedi tutti</a>
            <button class="slider-backward"><</button>
            <div class="slider-container">
                <ul class="slider-list">
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                </ul>
            </div>
            <button class="slider-forward">></button>
        </section>
        <section>
                <section>
            <h1>CATEGORIA DI PROVA</h1><a href="http://localhost:8080/category-products.php?category=0">Vedi tutti</a>
            <button class="slider-backward"><</button>
            <div class="slider-container">
                <ul class="slider-list">
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                </ul>
            </div>
            <button class="slider-forward">></button>
        </section>
                <section>
            <h1>CATEGORIA DI PROVA</h1><a href="http://localhost:8080/category-products.php?category=0">Vedi tutti</a>
            <button class="slider-backward"><</button>
            <div class="slider-container">
                <ul class="slider-list">
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                </ul>
            </div>
            <button class="slider-forward">></button>
        </section>
    <button class="slider-backward"><-</button>
                <section>
            <h1>CATEGORIA DI PROVA</h1><a href="http://localhost:8080/category-products.php?category=0">Vedi tutti</a>
            <button class="slider-backward"><</button>
            <div class="slider-container">
                <ul class="slider-list">
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                </ul>
            </div>
            <button class="slider-forward">></button>
        </section>
    <div class="slider-container">
                <section>
            <h1>CATEGORIA DI PROVA</h1><a href="http://localhost:8080/category-products.php?category=0">Vedi tutti</a>
            <button class="slider-backward"><</button>
            <div class="slider-container">
                <ul class="slider-list">
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                </ul>
            </div>
            <button class="slider-forward">></button>
        </section>
        <ul class="slider-list">
                <section>
            <h1>CATEGORIA DI PROVA</h1><a href="http://localhost:8080/category-products.php?category=0">Vedi tutti</a>
            <button class="slider-backward"><</button>
            <div class="slider-container">
                <ul class="slider-list">
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                    <li class="slider-object">PRODOTTO DI PROVA</li>
                </ul>
            </div>
            <button class="slider-forward">></button>
</section>

        <footer><!-- ?? Possible footer template ?? --></footer>
        <!-- INSERT HERE ALL JAVASCRIPT NECESSARY IMPORTS -->
        <script src="./dist/bootstrap5/js/bootstrap.min.js"></script>
        <script src="./dist/custom/js/cart-manager.js"></script>
        <?php if(isset($userRole) && ($userRole == Role::BUYER || $userRole == Role::VENDOR)): ?>
        <script>
            const $cartManager = new CartManager('badge3');
        </script>
        <?php endif; ?>
    </body>
</html>
