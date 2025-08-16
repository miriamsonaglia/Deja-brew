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
            use App\Models\Categoria;
            use App\Models\Prodotto;
            // QUERY: READ ALL CATEGORIES FROM DATABASE
            $categories = Categoria::all();
            $concatenated = array();
            foreach($categories as $category):
                // QUERY: READ ALL PRODUCTS FOR EACH CATEGORY FROM DATABASE
                array_push(
                    $concatenated,
                    [
                        "Category" => $category,
                        "Products" => Prodotto::all()->where("categoria_id", $category->id)
                    ]
                );
            endforeach;
            /**
             * The generated array "concatenate" its an array of associative array.
             * Every element has one Category and an array of products.
             */
        ?>
    </head>
    <body>
        <header><!-- ?? Possible header template ?? --></header>
        <?php include('./reusables/navbar.php'); ?>
        <!-- Slider template -->
        <?php
        foreach($concatenated as $object):
        ?>
        <section>
            <h1><?php echo $object['Category']->descrizione; ?></h1><a href="http://category-products.php?category=<?php echo $object['Category']->id; ?>">Vedi tutti</a>
            <button class="slider-backward"><-</button>
            <div class="slider-container">
                <ul class="slider-list">
                <?php foreach($object["Products"] as $product): ?>
                    <!-- At the moment the template is very basic -->
                    <li class="slider-object"><?php echo $product->nome; ?></li>
                <?php endforeach; ?>
                </ul>
            </div>
            <button class="slider-forward">-></button>
        </section>
        <?php endforeach;?>
        <footer><!-- ?? Possible footer template ?? --></footer>
        <!-- INSERT HERE ALL JAVASCRIPT NECESSARY IMPORTS -->
        <script src="./dist/bootstrap5/js/bootstrap.min.js"></script>
        <script src="./dist/custom/js/cart-manager.js"></script>
        <script>
            const $cartManager = new CartManager('badge3');
        </script>
    </body>
</html>
