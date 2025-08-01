<!DOCTYPE html>
<html>
    <head>
        <!-- INSERT HERE ALL CSS NECESSARY IMPORTS -->
        <link rel="stylesheet" href="./dist/fontawesome7/css/fontawesome.min.css">
        <link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
        <link rel="stylesheet" href="./dist/custom/css/style.css">
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
         <main>
            <h1><?php echo $category->descrizione; ?></h1>
            <ul>
            <?php
            foreach($products as $product):
            ?>
                <li>
                    <!-- Find a way to retrieve image from Database -->
                    <p><?php echo $product->nome; ?></p>
                    <input type="number" step="1"/>
                    <button class="shopping-bin-product"><i class="fa fa-trash"></i></button>
                </li>
            <?php endforeach;?>
            </ul>
         </main>
        <aside>
            <button class="filters-button"><i class="fa-light fa-filter"></i></button>
            <!-- Tendina apribile con lista filtri -->
        </aside>
        <footer><!-- ?? Possible footer template ?? --></footer>
    </body>
    <script>
    </script>
</html>
