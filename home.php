<!DOCTYPE html>
<html lang="it">
    <head>
        <link rel="stylesheet" href="./dist/bootstrap5/icons/bootstrap-icons.css">
        <link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
        <link rel="stylesheet" href="./dist/custom/css/new-style.css">
        <title>Deja Brew - Home</title>
        <?php
            // This in-page script initialize all the required php imports and populates the datasets.
            require_once __DIR__ . '/bootstrap.php';
            require_once __DIR__ . '/Models/Categoria.php';
            require_once __DIR__ . '/Models/Prodotto.php';
            require_once __DIR__ . '/role.php';
            require_once __DIR__ . '/utilities.php';
            require_once __DIR__ . '/Models/UtenteCompratore.php';
            use App\Models\Categoria;
            use App\Models\Prodotto;
            use App\Models\UtenteCompratore;
            session_start();
            if(isset($_SESSION['LoggedUser'])) {
                $utenteCompratore = UtenteCompratore::where('id_utente', $_SESSION['LoggedUser']['id'])->first();
            }
            $categories = Categoria::all();
        ?>
    </head>
    <body>
        <?php require_once __DIR__ . '/navbar-selector.php'; ?>
        <div class="container-fluid">
         <div class="search-wrapper position-relative mx-auto" style="max-width: 600px;">
            <label for="live-search" class="visually-hidden">Cerca prodotti</label>
            <input type="text"
                   class="search-bar form-control"
                   id="live-search"
                   placeholder="Cerca prodotti..."
                   autocomplete="off"/>

            <ul id="search-results"
                class="list-group position-absolute w-100"
                style="z-index: 1050; top: 100%; display: none;"></ul>
        </div>

            <!-- Categories with Enhanced Slider -->
            <?php
            foreach($categories as $index => $category):
                $products = Prodotto::where('categoria_id', $category->id)->limit(50)->get();
                $sliderId = 'category-' . $category->id;
                if($products->isEmpty()):
                    continue;
                else:
            ?>
            <section class="category-section">
                <div class="category-header">
                    <h1 class="category-title"><?php echo htmlspecialchars($category->descrizione); ?></h1>
                    <a href="./category-products.php?category=<?php echo $category->id; ?>" class="view-all-link" aria-label="Vedi tutti i prodotti della categoria <?php echo htmlspecialchars($category->descrizione); ?>">Vedi tutti</a>
                </div>

                <div class="slider-wrapper" role ="region" aria-label="Prodotti della categoria <?php echo htmlspecialchars($category->descrizione); ?>">
                    <button class="slider-backward" data-slider="<?php echo $sliderId; ?>" aria-label="Scorri indietro i prodotti della categoria <?php echo htmlspecialchars($category->descrizione); ?>">
                        <i class="bi bi-arrow-left" aria-hidden="true"></i>
                    </button>

                    <div class="slider-container">
                        <ul class="slider-list" id="<?php echo $sliderId; ?>-slider">
                            <?php foreach($products as $product): ?>
                            <li class="slider-object" data-product-id="<?php echo $product->id; ?>">
                                <a href="product.php?id=<?php echo $product->id; ?>" class="text-decoration-none text-dark" aria-label="Vai alla pagina del prodotto <?php echo htmlspecialchars($product->nome); ?>">
                                    <img src="<?php echo (empty($product->fotografia) ?
                                                                        ('./images/products/Standard_Blend.png') :
                                                                        (file_exists('./uploads/prodotti/' . htmlspecialchars($product->fotografia)) ?
                                                                                                './uploads/prodotti/' . htmlspecialchars($product->fotografia) :
                                                                                                './images/products/Standard_Blend.png')); ?>"
                                        alt="<?php echo htmlspecialchars($product->nome); ?>">
                                    <div class="product-name"><?php echo htmlspecialchars($product->nome); ?></div>
                                </a>
                                <div class="product-price"><?php echo number_format($product->prezzo, 2); ?> €</div>

                                <?php if(isset($userRole) && ($userRole == Role::BUYER->value)): ?>
                                    <label for="quantity-<?php echo $product->id; ?>" class="visually-hidden">
                                        Quantità per <?php echo htmlspecialchars($product->nome); ?>
                                    </label>
                                    <input type="number"
                                        id="quantity-<?php echo $product->id; ?>"
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
                                    <?php if(wished($product->id, $utenteCompratore->id)): ?>
                                            title="Rimuovi dalla wishlist">
                                            <i class="bi bi-heart-fill" aria-hidden="true"></i>
                                        <?php else: ?>
                                            title="Aggiungi alla wishlist">
                                            <i class="bi bi-heart" aria-hidden="true"></i>
                                        <?php endif; ?>
                                    </button>
                                <?php endif; ?>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>

                    <button class="slider-forward" data-slider="<?php echo $sliderId; ?>" aria-label="Scorri avanti i prodotti della categoria <?php echo htmlspecialchars($category->descrizione); ?>">
                        <i class="bi bi-arrow-right" aria-hidden="true"></i>
                    </button>
                </div>

                <!-- Slider Indicators -->
                <div class="slider-indicators" id="<?php echo $sliderId; ?>-indicators"></div>
            </section>
            <?php
                endif;
                endforeach;
            ?>
        </div>

        <?php require_once __DIR__ . '/reusables/footer.php' ?>

        <script src="./dist/bootstrap5/js/bootstrap.min.js"></script>
        <script src="./dist/custom/js/home-slider-manager.js"></script>
        <script src="./dist/custom/js/searchbar-manager.js"></script>
        <?php if(isset($userRole) && ($userRole === Role::BUYER->value)): ?>
            <script src="./dist/custom/js/wishlist-manager.js"></script>
            <script src="./dist/custom/js/cart-manager.js"></script>
            <script src="./dist/custom/js/input-validation.js"></script>
        <?php endif; ?>

        <script>
            // Initialize everything when DOM is ready
            document.addEventListener('DOMContentLoaded', function() {
                // Initialize sliders for each category
                <?php foreach($categories as $category): ?>
                    new ProductSlider('category-<?php echo $category->id;?>-slider');
                <?php endforeach; ?>
                <?php if(isset($userRole) && ($userRole === Role::BUYER->value)): ?>
                    updateCartCount();
                <?php endif; ?>
            });


        </script>
    </body>
</html>