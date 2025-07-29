<!DOCTYPE html>
<html>
    <head>
        <!-- INSERT HERE ALL CSS AND JAVASCRIPT NECESSARY IMPORTS -->
        <?php
            // This in-page script initialize all the required php imports and populates the datasets.
            use App\Models\Prodotto;
            use App\Models\Categoria;
            require_once __DIR__ . '/bootstrap.php';
            require_once __DIR__ . '/Models/Categoria.php';
            require_once __DIR__ . '/Models/Prodotto.php';
            // READ QUERY: ALL PRODUCTS OF THE CHOSEN CATEGORY
            $category = Categoria::find($_GET['category']);
            if($category ==  null) { 
                $category = new Categoria();
                $category->descrizione = "CATEGORIA PLACEHOLDER";
            }
            $products = Prodotto::where("categoria_id", $_GET['category']);
        ?>
    </head>
    <body>
        <header><!-- ?? Possible header template ?? --></header>
        <nav><!-- This will be replaced by the navbar template --></nav>
        <!-- Slider template -->
        <section>
            <h1><?php echo $category->descrizione; ?></h1>
            <ul>
            <?php
            foreach($products as $product):
            ?>
                <li>
                    <!-- Find a way to retrieve image from Database -->
                    <p><?php echo $product->nome; ?></p>
                    <input type="number" step="1"/>
                    <button class="shopping-bin-product">Cassonetto</button>
                </li>
            <?php endforeach;?>
            </ul>
        </section>
        <aside>
            <button class="filters-button">Filtri</button>
            <!-- Tendina apribile con lista filtri -->
        </aside>
        <footer><!-- ?? Possible footer template ?? --></footer>
    </body>
    <script>
    </script>
</html>
