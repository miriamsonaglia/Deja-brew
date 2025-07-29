<!DOCTYPE html>
<html>
    <head>
        <!-- INSERT HERE ALL CSS AND JAVASCRIPT NECESSARY IMPORTS -->
        <?php
            // This in-page script initialize all the required php imports and populates the datasets.
            use App\Models\Categoria;
            use App\Models\Prodotto;
            require_once __DIR__ . '/bootstrap.php';
            require_once __DIR__ . '/Models/Categoria.php';
            require_once __DIR__ . '/Models/Prodotto.php';
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
        <nav><!-- This will be replaced by the navbar template --></nav>
        <!-- Slider template -->
        <?php
        foreach($concatenated as $object):
        ?>
        <section>
            <h1><?php echo $object['Category']->descrizione; ?></h1><a href="http://somegeturl/params<?php echo $object['Category']->id; ?>">Vedi tutti</a>
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
    </body>
    <script>
    </script>
</html>
