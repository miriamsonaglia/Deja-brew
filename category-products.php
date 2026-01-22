<?php
    require_once __DIR__ . '/bootstrap.php';
    require_once __DIR__ . '/utilities.php';
    require_once __DIR__ . '/Models/Categoria.php';
    require_once __DIR__ . '/Models/Prodotto.php';
    require_once __DIR__ . '/Models/UtenteCompratore.php';
    require_once __DIR__ . '/Models/Aroma.php';
    require_once __DIR__ . '/role.php';
    use App\Models\Prodotto;
    use App\Models\Categoria;
    use App\Models\UtenteCompratore;
    session_start();
    $utenteCompratore = UtenteCompratore::where('id_utente', $_SESSION['LoggedUser']['id'])->first();
    $category = Categoria::find($_GET['category']);
    $products = Prodotto::where("categoria_id", $category->id)->get();
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title><?php echo $category->descrizione; ?> - Deja-brew</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSS -->
    <link rel="stylesheet" href="./dist/bootstrap5/icons/bootstrap-icons.css">
    <link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
    <link rel="stylesheet" href="./dist/custom/css/new-style.css">
</head>
<body>
<header></header>
<?php require_once __DIR__ . '/navbar-selector.php'; ?>
<main>
    <div class="container-fluid">
        <div class="category-section my-4">
            <!-- Sostituisci questo blocco nel tuo category-products.php: -->
            <div class="category-section my-4">
                <div class="category-header">
                    <a href="./home.php" class="back-button" title="Torna alla home" aria-label="Torna alla home">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    </a>
                    <h2 class="category-title"><?php echo htmlspecialchars($category->descrizione); ?></h2>
                </div>

                <div class="product-grid d-flex flex-wrap justify-content-center gap-4">
                    <!-- Il resto del codice rimane uguale -->
                    <?php foreach ($products as $product): ?>
                                            <div class="slider-object card card-product" style="width: 18rem;"
                        data-product-id="<?php echo $product->id; ?>"
                        data-product-name="<?php echo htmlspecialchars($product->nome); ?>"
                        data-product-price="<?php echo $product->prezzo; ?>"
                        data-product-aroma="<?php echo htmlspecialchars($product->aroma ? $product->aroma->gusto : ''); ?>"
                        data-product-provenienza="<?php echo htmlspecialchars($product->provenienza ?? ''); ?>"
                        data-product-weight="<?php echo $product->peso ?? 0; ?>">

                        <img src="<?php echo (empty($product->fotografia) ? 
                                                            ('./images/products/Standard_Blend.png') : 
                                                            (file_exists('./uploads/prodotti/' . htmlspecialchars($product->fotografia)) ? 
                                                                        './uploads/prodotti/' . htmlspecialchars($product->fotografia) : 
                                                                        './images/products/Standard_Blend.png')); ?>"
                             class="card-img-top"
                             alt="<?php echo htmlspecialchars($product->nome); ?>">

                        <div class="card-body text-center">
                            <div class="product-name mb-2"><?php echo htmlspecialchars($product->nome); ?></div>
                            <div class="product-price mb-3"><?php echo number_format($product->prezzo, 2); ?> €</div>
                                <?php if($_SESSION['UserRole'] === Role::BUYER->value): ?>
                                    <div class="d-flex align-items-center justify-content-center gap-2 mb-3">
                                        <label for="quantity-<?php echo $product->id; ?>" class="visually-hidden">Quantità per <?php echo htmlspecialchars($product->nome); ?></label>
                                        <input type="number"
                                               step="1"
                                               value="1"
                                               min="1"
                                               class="quantity-input"
                                               id="quantity-<?php echo $product->id; ?>"
                                               data-product-id="<?php echo $product->id; ?>">
                                    </div>

                                    <button class="btn btn-primary-custom w-100 mb-2 cart-button"
                                            data-product-id="<?php echo $product->id; ?>"
                                            data-product-name="<?php echo htmlspecialchars($product->nome); ?>"
                                            data-product-price="<?php echo $product->prezzo; ?>">
                                        Aggiungi al carrello
                                    </button>
                                    <?php if(wished($product->id, $utenteCompratore->id_utente)): ?>
                                        <button class="btn btn-outline-danger wish-button"
                                                data-product-id="<?php echo $product->id; ?>"
                                                title="Rimuovi dalla wishlist">
                                            <i class="bi bi-heart-fill"></i>
                                        </button>
                                    <?php else: ?>
                                    <button class="btn btn-outline-primary-custom wish-button"
                                            data-product-id="<?php echo $product->id; ?>"
                                            title="Aggiungi alla wishlist">
                                        <i class="bi bi-heart"></i>
                                    </button>
                                    <?php endif; ?>
                                <?php endif; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
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
            updateCartCount();
            new Filter();
        });
    </script>
</body>
</html>
