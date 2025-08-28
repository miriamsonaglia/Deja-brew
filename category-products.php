<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Home - Deja-brew</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <!-- CSS -->
    <link rel="stylesheet" href="./dist/bootstrap5/icons/bootstrap-icons.css">
    <link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
    <link rel="stylesheet" href="./dist/custom/css/new-style.css">

    <?php
        require_once __DIR__ . '/bootstrap.php';
        require_once __DIR__ . '/Models/Categoria.php';
        require_once __DIR__ . '/Models/Prodotto.php';
        require_once __DIR__ . '/Models/Aroma.php';
        require_once __DIR__ . '/role.php';
        use App\Models\Prodotto;
        use App\Models\Categoria;
        session_start();
        $category = Categoria::find($_GET['category']);
        $products = Prodotto::where("categoria_id", $_GET['category'])->get();
    ?>
</head>
<body>
<header></header>
<?php require_once __DIR__ . '/navbar-selector.php'; ?>
<main>
    <div class="container-fluid">
        <div class="category-section my-4">
            <div class="category-header text-center mb-4">
                <a href="home.php"><i class="bi bi-arrow-left"></i></a><h1 class="category-title"><?php echo htmlspecialchars($category->descrizione); ?></h1>
            </div>

            <div class="product-grid d-flex flex-wrap justify-content-center gap-4">
                <?php foreach ($products as $product): ?>
                    <div class="slider-object card card-product" style="width: 18rem;" 
                        data-product-id="<?php echo $product->id; ?>"
                        data-product-name="<?php echo htmlspecialchars($product->nome); ?>"
                        data-product-price="<?php echo $product->prezzo; ?>"
                        data-product-aroma="<?php echo htmlspecialchars($product->aroma ? $product->aroma->gusto : ''); ?>"
                        data-product-provenienza="<?php echo htmlspecialchars($product->provenienza ?? ''); ?>"
                        data-product-weight="<?php echo $product->peso ?? 0; ?>">
                        
                        <img src="<?php echo htmlspecialchars($product->fotografia); ?>" 
                             class="card-img-top"
                             alt="<?php echo htmlspecialchars($product->nome); ?>">
                        
                        <div class="card-body text-center">
                            <div class="product-name mb-2"><?php echo htmlspecialchars($product->nome); ?></div>
                            <div class="product-price mb-3"><?php echo number_format($product->prezzo, 2); ?> €</div>

                                <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                                    <input type="number"
                                           step="1"
                                           value="1"
                                           min="1"
                                           class="quantity-input"
                                           data-product-id="<?php echo $product->id; ?>">
                                </div>

                                <button class="btn btn-primary-custom w-100 mb-2 cart-button"
                                        data-product-id="<?php echo $product->id; ?>"
                                        data-product-name="<?php echo htmlspecialchars($product->nome); ?>"
                                        data-product-price="<?php echo $product->prezzo; ?>">
                                    Aggiungi al carrello
                                </button>

                                <button class="btn btn-outline-primary-custom w-100 wish-button"
                                        data-product-id="<?php echo $product->id; ?>"
                                        title="Aggiungi alla wishlist">
                                    <i class="bi bi-heart me-2"></i>
                                </button>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</main>

<?php include('./reusables/filter-aside.php'); ?>

<footer></footer>

<!-- JavaScript -->
<script src="./dist/bootstrap5/js/bootstrap.min.js"></script>
<script src="./dist/custom/js/sidebar-manager.js"></script>

    <script src="./dist/custom/js/wishlist-manager.js"></script>
    <script src="./dist/custom/js/cart-manager.js"></script>
    <script src="./dist/custom/js/input-validation.js"></script>
    <script src="./dist/custom/js/filter.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            new Filter();
        });
    </script>
</body>
</html>
