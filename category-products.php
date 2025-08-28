<!DOCTYPE html>
<html>
    <meta charset="UTF-8">
    <title>Home - Deja-brew</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
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
            require_once __DIR__ . '/Models/Aroma.php';
            require_once __DIR__ . '/role.php';
            use App\Models\Prodotto;
            use App\Models\Categoria;
            session_start();
            $userRole = $_SESSION['UserRole'] ?? Role::GUEST->value;
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
        <?php 
            switch($userRole) {
                case Role::GUEST->value:
                    include('./reusables/navbars/empty-navbar.php');
                    break;
                case Role::BUYER->value:
                    include('./reusables/navbars/buyer-navbar.php');
                    break;
                case Role::VENDOR->value:
                    include('./reusables/navbars/vendor-navbar.php');
                    break;
                default:
                    include('./reusables/navbars/empty-navbar.php');
                    break;
            }
        ?>
        <main>
            <div class="container-fluid">
                <div class="category-section">
                    <div class="category-header">
                        <h1 class="category-title"><?php echo htmlspecialchars($category->descrizione); ?></h1>
                    </div>
                    
                    <div class="product-grid-container">
                        <ul class="product-grid">
                            <?php foreach($products as $product): ?>
                            <li class="slider-object"
                                data-product-id="<?php echo $product->id;?>"
                                data-product-name="<?php echo htmlspecialchars($product->nome); ?>"
                                data-product-price="<?php echo $product->prezzo; ?>"
                                data-product-aroma="<?php echo htmlspecialchars($product->aroma ? $product->aroma->gusto : ''); ?>"
                                data-product-provenienza="<?php echo htmlspecialchars($product->provenienza ?? ''); ?>"
                                data-product-weight="<?php echo $product->peso ?? 0; ?>">
                                <img src="<?php echo htmlspecialchars($product->fotografia); ?>" 
                                     alt="<?php echo htmlspecialchars($product->nome); ?>">
                                <div class="product-name"><?php echo htmlspecialchars($product->nome); ?></div>
                                <div class="product-price"><?php echo number_format($product->prezzo, 2); ?> €</div>
                                <?php if(isset($userRole) && ($userRole == Role::BUYER)): ?>
                                <input type="number" 
                                       step="1" 
                                       value="1" 
                                       min="1" 
                                       class="quantity-input"
                                       data-product-id="<?php echo $product->id; ?>">
                                <button class="cart-button" 
                                        data-product-id="<?php echo $product->id; ?>"
                                        data-product-name="<?php echo htmlspecialchars($product->nome); ?>"
                                        data-product-price="<?php echo $product->prezzo; ?>">
                                    Aggiungi al carrello
                                </button>
                                <button class="wish-button" 
                                        data-product-id="<?php echo $product->id; ?>"
                                        title="Aggiungi alla wishlist">
                                    <i class="bi bi-heart"></i>
                                </button>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </main>
        
        <?php include('./reusables/filter-aside.php'); ?>
        
        <footer><!-- ?? Possible footer template ?? --></footer>
        
        <!-- INSERT HERE ALL JAVASCRIPT NECESSARY IMPORTS -->
        <script src="./dist/bootstrap5/js/bootstrap.min.js"></script>
        <script src="./dist/custom/js/sidebar-manager.js"></script>
        
        <?php if(isset($userRole) && ($userRole == Role::BUYER->value)): ?>
            <script src="./dist/custom/js/wishlist-manager.js"></script>
            <script src="./dist/custom/js/cart-manager.js"></script>
            <script src="./dist/custom/js/input-validation.js"></script>
            <script src="./dist/custom/js/filter.js"></script>
        <?php endif; ?>
    </body>
    
    <script>
        // Filters functionality
        <?php if(isset($userRole) && ($userRole == Role::BUYER->value)): ?>
        document.addEventListener('DOMContentLoaded', function() {
            new Filter();
        });
        <?php endif; ?>
    </script>
</html>