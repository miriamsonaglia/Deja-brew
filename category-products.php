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
            // READ QUERY: ALL PRODUCTS OF THE CHOSEN CATEGORY
            $category = Categoria::find($_GET['category']);
            if($category ==  null) { 
                $category = new Categoria();
                $category->descrizione = "CATEGORIA PLACEHOLDER";
            }
            $products = Prodotto::where("categoria_id", $_GET['category'])->get();
        ?>
    </head>
    <body>
        <header><!-- ?? Possible header template ?? --></header>
        <?php include('./reusables/navbars/buyer-navbar.php'); ?>
        <main>
            <h1><?php echo $category->descrizione; ?></h1>
            <ul class="product-list">
            <?php foreach($products as $product): ?>
                <li class="product-list-element">
                    <img src="<?php echo $product->fotografia; ?>" class="product-picture" alt="<?php echo $product->nome; ?>">
                    <input type="text" value="<?php echo $product->id; ?>" hidden/>
                    <p class="product-name"><?php echo $product->nome; ?></p>
                    <input type="number" step="1" class="product-quantity" min="0" value="0"/>
                    <button class="add-to-cart-button">Aggiungi al carrello</button>
                </li>
            <?php endforeach;?>
            </ul>
        </main>
        <aside>
            <button class="filters-button"><i class="bi bi-filter"></i></button>
            <!-- Tendina apribile con lista filtri -->
        </aside>
        <footer><!-- ?? Possible footer template ?? --></footer>
        <!-- INSERT HERE ALL JAVASCRIPT NECESSARY IMPORTS -->
        <script src="./dist/bootstrap5/js/bootstrap.min.js"></script>
        <script src="./dist/custom/js/sidebar-manager.js"></script>
    </body>
    <script>
       let addToCartButtons = document.querySelectorAll("main ul li .add-to-cart-button");

        addToCartButtons.forEach((button) => {
            button.addEventListener('click', () => {
                let li = button.closest('li');
                let productIDInput = li.querySelector('input[type=text]');
                let numberInput = li.querySelector('input[type=number]');

                let quantity = numberInput ? parseInt(numberInput.value) : 0;
                let productID = productIDInput ? productIDInput.value : -1;

                console.log('Aggiungo al carrello: ' + quantity + " di " + productID);

                // Se quantity è 0 o productID non valido, esci
                if (quantity <= 0 || productID === -1) {
                    console.warn('Quantità o ID prodotto non validi');
                    return;
                }

                // AJAX con Fetch API
                fetch('./cart-add-product.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        productID: productID,
                        quantity: quantity
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error("Errore nella risposta del server");
                    }
                    return response.json();
                })
                .then(data => {
                    console.log('Risposta dal server:', data);
                    // Refresh del carrello
                })
                .catch(error => {
                    console.error('Errore durante la richiesta:', error);
                });
            });
        }); 


        let filterButton = document.querySelector("aside .filters-button");
    </script>
</html>
