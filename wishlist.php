<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lista Desideri - Deja-brew</title>
        
        <!-- Bootstrap 5 CSS -->
        <link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
        <link rel="stylesheet" href="./dist/bootstrap5/icons/bootstrap-icons.css">
        <link rel="stylesheet" href="./dist/custom/css/new-style.css">
        
        <?php
            use App\Models\UtenteCompratore;
            use App\Models\Prodotto;
            use App\Models\Lista;

            require_once __DIR__ . '/bootstrap.php';
            require_once __DIR__ . '/Models/UtenteCompratore.php';
            require_once __DIR__ . '/Models/Prodotto.php';
            require_once __DIR__ . '/Models/Lista.php';
            require_once __DIR__ . '/role.php';
            
            session_start();
            if(!isset($_SESSION['LoggedUser']['id']) || $_SESSION['UserRole'] != Role::BUYER->value){
                header("Location: login.php");
                exit;
            }
            
            // --- Recupero utente compratore senza usare la relazione ---
            $utenteCompratore = UtenteCompratore::where('id_utente', $_SESSION['LoggedUser']['id'])->first();
            // --- Recupero prodotti nel carrello ---
            $user_wishlist = Lista::where('id_utente_compratore', $utenteCompratore->id)
                            ->where('tipo', 'desideri')
                            ->get();
        ?>
    </head>
    <body class="bg-cream">
        <header></header>
        <?php include('./reusables/navbars/buyer-navbar.php'); ?>
        <div class="container-fluid">
            <div class="wishlist-content">
                <?php if(count($user_wishlist) > 0): ?>
                <!-- Wishlist Header -->
                <div class="wishlist-header text-center">
                    <i class="bi bi-heart-fill me-2"></i>
                    <span>La tua Lista Desideri (<?php echo count($user_wishlist); ?> articoli)</span>
                </div>
                
                <!-- Wishlist Items -->
                <div class="wishlist-items">
                    <?php foreach($user_wishlist as $wishlist_item): 
                        $product = Prodotto::where('id', $wishlist_item->id_prodotto)->first();
                        if($product):
                    ?>
                    <div class="wishlist-item" data-product-id="<?php echo $product->id; ?>">
                        <div class="row align-items-center">
                            <!-- Product Image -->
                            <div class="col-md-2 col-sm-3">
                                <img src="<?php echo htmlspecialchars(empty($product->fotografia) ? './images/products/Standard_Blend.png' : $product->fotografia); ?>" 
                                     alt="<?php echo htmlspecialchars($product->nome); ?>" 
                                     class="wishlist-product-image img-fluid rounded">
                            </div>
                            
                            <!-- Product Info -->
                            <div class="col-md-6 col-sm-5">
                                <div class="d-flex flex-column">
                                    <a href="product.php?id=<?php echo $product->id; ?>" 
                                        class="cart-product-name text-decoration-none mb-1">
                                        <?php echo htmlspecialchars($product->nome); ?>
                                    </a>
                                    <small class="text-muted" data-price="<?php echo $product->prezzo;?>">
                                        € <?php echo number_format($product->prezzo, 2); ?> cad.
                                    </small>
                                    <?php if($product->descrizione): ?>
                                    <small class="text-muted mt-1">
                                        <?php echo htmlspecialchars(substr($product->descrizione, 0, 100)); ?>...
                                    </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <div class="col-md-2 col-sm-2">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <button class="cart-quantity-btn btn decrease-btn" 
                                                    onclick="decreaseQuantity(<?php echo $product->id;?>)">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <!-- Quantity Input: Link onchange with cart logic -->
                                            <input type="text" 
                                                   value="<?php echo $wishlist_item->quantita; ?>" 
                                                   class="form-control mx-2 text-center quantity-input" 
                                                   style="width: 60px;"
                                                   data-product-id="<?php echo $product->id; ?>"
                                                   readonly>
                                            <button class="cart-quantity-btn btn increase-btn" 
                                                    onclick="increaseQuantity(<?php echo $product->id;?>)">
                                                <i class="bi bi-plus"></i>
                                            </button>
                                        </div>
                                    </div>
                            
                            <!-- Action Buttons -->
                            <div class="col-md-2 col-sm-2">
                                <div class="d-flex flex-column gap-2">
                                    <button class="cart-button" 
                                            data-product-id="<?php echo $product->id; ?>"
                                            data-product-name="<?php echo htmlspecialchars($product->nome); ?>"
                                            data-product-price="<?php echo $product->prezzo; ?>"
                                            title="Aggiungi al carrello">
                                        <i class="bi bi-cart-plus me-1"></i>
                                        <span class="d-none d-md-inline">Carrello</span>
                                    </button>
                                    <div class="justify-content-center align-items-center d-flex">
                                    <button class="wish-button btn btn-outline-danger btn-sm"
                                            data-product-id="<?php echo $product->id; ?>"
                                            title="Rimuovi dalla lista desideri">
                                        <i class="bi bi-heart-fill"></i>
                                    </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>
                
                <!-- Wishlist Actions -->
                <!-- TODO SE RIMANE TEMPO
                <div class="bg-white rounded shadow-custom p-4 mt-4">
                    <div class="row">
                        <div class="col-md-6">
                            <h5 class="text-primary-brown mb-3">
                                <i class="bi bi-gift me-2"></i>Condividi la tua lista
                            </h5>
                            <p class="text-muted small mb-3">
                                Condividi la tua lista desideri con amici e familiari
                            </p>
                            <button class="btn btn-outline-primary-custom">
                                <i class="bi bi-share me-2"></i>Condividi Lista
                            </button>
                        </div>
                        <div class="col-md-6">
                            <h5 class="text-primary-brown mb-3">
                                <i class="bi bi-cart-check me-2"></i>Azioni rapide
                            </h5>
                            <div class="d-flex flex-wrap gap-2">
                                <button class="btn btn-primary-custom" onclick="addAllToCart()">
                                    <i class="bi bi-cart-plus me-1"></i>Aggiungi tutto al carrello
                                </button>
                                <button class="btn btn-outline-danger" onclick="clearWishlist()">
                                    <i class="bi bi-trash me-1"></i>Svuota lista
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                -->
                <?php else: ?>
                <!-- Empty Wishlist State -->
                <div class="empty-state">
                    <i class="bi bi-heart display-1 text-muted mb-4"></i>
                    <h3 class="text-primary-brown mb-3">La tua lista desideri è vuota</h3>
                    <p class="text-muted mb-4">
                        Aggiungi prodotti che ti interessano per trovarli facilmente in seguito!
                    </p>
                    <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                        <a href="home.php" class="btn btn-primary-custom">
                            <i class="bi bi-shop me-2"></i>
                            Esplora Prodotti
                        </a>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <footer></footer>
        
        <!-- Bootstrap 5 JS -->
        <script src="./dist/bootstrap5/js/bootstrap.min.js"></script>
        <script src="./dist/custom/js/wishlist-manager.js"></script>
        <script src="./dist/custom/js/cart-manager.js"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                updateCartCount();
            });
            
            function increaseQuantity(productId) {
                const input = document.querySelector(`input[data-product-id='${productId}']`);
                let currentValue = parseInt(input.value);
                input.value = currentValue + 1;
            }

            function decreaseQuantity(productId) {
                const input = document.querySelector(`input[data-product-id='${productId}']`);
                let currentValue = parseInt(input.value);
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                }
            }
            // Make compatibility check with wishlist manager
        </script>
    </body>
</html>