<!DOCTYPE html>
<html>
    <head>
        <!-- INSERT HERE ALL CSS NECESSARY IMPORTS -->
        <link rel="stylesheet" href="./dist/bootstrap5/icons/bootstrap-icons.css">
        <link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
        <link rel="stylesheet" href="./dist/custom/css/style.css">
        <?php
            // This in-page script initialize all the required php imports and populates the datasets.
            require_once __DIR__ . '/bootstrap.php';
            require_once __DIR__ . '/Models/Categoria.php';
            require_once __DIR__ . '/Models/Prodotto.php';
            use App\Models\Prodotto;
            use App\Models\Categoria;
            /**
             * CREATE LOGIC TO RETRIEVE FROM DATABASE ALL PRODUCTS WITH NAME OR DESCRIPTION LIKE THE SEARCHED
             */
        ?>
    </head>
    <body>
        <header><!-- ?? Possible header template ?? --></header>
        <?php include('./reusables/navbars/buyer-navbar.php'); ?>
        <!-- Slider template -->
        <main>
            <h1><?php echo $category->descrizione; ?></h1>
            <ul class="product-list">
            <?php foreach($products as $product): ?>
                <li class="product-list-element">
                    <img src="<?php echo $product->fotografia; ?>" class="product-picture" alt="<?php echo $product->nome; ?>">
                    <p class="product-name"><?php echo $product->nome; ?></p>
                    <input type="number" step="1" class="product-quantity" min="0" value="0"/>
                    <button class="add-to-cart-button">Aggiungi al carrello</button>
                    <button class="bin-button"><i class="bi bi-trash"></i></button>
                </li>
            <?php endforeach;?>
            <!-- ELEMENTI DI PROVA -->
             <li class="product-list-element">
                    <img src="" class="product-picture" alt="EMPTY">
                    <p class="product-name">Elemento di prova</p>
                    <input type="number" step="1" class="product-quantity" min="0" value="0"/>
                    <button class="add-to-cart-button">Aggiungi al carrello</button>
                    <button class="bin-button"><i class="bi bi-trash"></i></button>
                </li>
                <li class="product-list-element">
                    <img src="" class="product-picture" alt="EMPTY">
                    <p class="product-name">Elemento di prova</p>
                    <input type="number" step="1" class="product-quantity" min="0" value="0"/>
                    <button class="add-to-cart-button">Aggiungi al carrello</button>
                    <button class="bin-button"><i class="bi bi-trash"></i></button>
                </li>
                <li class="product-list-element">
                    <img src="" class="product-picture" alt="EMPTY">
                    <p class="product-name">Elemento di prova</p>
                    <input type="number" step="1" class="product-quantity" min="0" value="0"/>
                    <button class="add-to-cart-button">Aggiungi al carrello</button>
                    <button class="bin-button"><i class="bi bi-trash"></i></button>
                </li>
                <li class="product-list-element">
                    <img src="" class="product-picture" alt="EMPTY">
                    <p class="product-name">Elemento di prova</p>
                    <input type="number" step="1" class="product-quantity" min="0" value="0"/>
                    <button class="add-to-cart-button">Aggiungi al carrello</button>
                    <button class="bin-button"><i class="bi bi-trash"></i></button>
                </li>
                  <li class="product-list-element">
                    <img src="" class="product-picture" alt="EMPTY">
                    <p class="product-name">Elemento di prova</p>
                    <input type="number" step="1" class="product-quantity" min="0" value="0"/>
                    <button class="add-to-cart-button">Aggiungi al carrello</button>
                    <button class="bin-button"><i class="bi bi-trash"></i></button>
                </li>
                <li class="product-list-element">
                    <img src="" class="product-picture" alt="EMPTY">
                    <p class="product-name">Elemento di prova</p>
                    <input type="number" step="1" class="product-quantity" min="0" value="0"/>
                    <button class="add-to-cart-button">Aggiungi al carrello</button>
                    <button class="bin-button"><i class="bi bi-trash"></i></button>
                </li>
                <li class="product-list-element">
                    <img src="" class="product-picture" alt="EMPTY">
                    <p class="product-name">Elemento di prova</p>
                    <input type="number" step="1" class="product-quantity" min="0" value="0"/>
                    <button class="add-to-cart-button">Aggiungi al carrello</button>
                    <button class="bin-button"><i class="bi bi-trash"></i></button>
                </li>
                <li class="product-list-element">
                    <img src="" class="product-picture" alt="EMPTY">
                    <p class="product-name">Elemento di prova</p>
                    <input type="number" step="1" class="product-quantity" min="0" value="0"/>
                    <button class="add-to-cart-button">Aggiungi al carrello</button>
                    <button class="bin-button"><i class="bi bi-trash"></i></button>
                </li>
            </ul>
        </main>
        <aside>
            <button class="filters-button"><i class="fa-light fa-filter"></i></button>
            <!-- Tendina apribile con lista filtri -->
        </aside>
        <footer><!-- ?? Possible footer template ?? --></footer>
        <!-- INSERT HERE ALL JAVASCRIPT NECESSARY IMPORTS -->
        <script src="./dist/bootstrap5/js/bootstrap.min.js"></script>
        <script src="./dist/custom/js/sidebar-manager.js"></script>
    </body>
    <script>
        let addToCartButtons = document.querySelectorAll("main ul li .add-to-cart-button");
        let trashButtons = document.querySelectorAll("main ul li .bin-button");
        let filtersButton = document.querySelector("aside .filters-button");
    </script>
</html>
