<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Lista Desideri - Deja-brew</title>
        
        <!-- Bootstrap 5 CSS -->
        <link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
        <link rel="stylesheet" href="./dist/bootstrap5/icons/bootstrap-icons.css">
        <link rel="stylesheet" href="./dist/custom/css/style.css">
        
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
                                <img src="<?php echo htmlspecialchars($product->fotografia); ?>" 
                                     alt="<?php echo htmlspecialchars($product->nome); ?>" 
                                     class="wishlist-product-image img-fluid rounded">
                            </div>
                            
                            <!-- Product Info -->
                            <div class="col-md-6 col-sm-5">
                                <div class="d-flex flex-column">
                                    <a href="product.php?id=<?php echo $product->id; ?>" 
                                       class="wishlist-product-name text-decoration-none mb-2">
                                        <?php echo htmlspecialchars($product->nome); ?>
                                    </a>
                                    <div class="wishlist-product-price">
                                        € <?php echo number_format($product->prezzo, 2); ?>
                                    </div>
                                    <?php if($product->descrizione): ?>
                                    <small class="text-muted mt-1">
                                        <?php echo htmlspecialchars(substr($product->descrizione, 0, 100)); ?>...
                                    </small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            
                            <!-- Quantity Input -->
                            <div class="col-md-2 col-sm-2">
                                <div class="d-flex flex-column align-items-center">
                                    <label class="form-label small text-muted mb-1">Quantità</label>
                                    <input type="number" 
                                           step="1" 
                                           value="1" 
                                           min="1" 
                                           class="form-control form-control-custom text-center"
                                           style="width: 70px;"
                                           data-product-id="<?php echo $product->id; ?>">
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="col-md-2 col-sm-2">
                                <div class="d-flex flex-column gap-2">
                                    <button class="btn btn-primary-custom btn-sm" 
                                            data-product-id="<?php echo $product->id; ?>"
                                            data-product-name="<?php echo htmlspecialchars($product->nome); ?>"
                                            data-product-price="<?php echo $product->prezzo; ?>"
                                            title="Aggiungi al carrello">
                                        <i class="bi bi-cart-plus me-1"></i>
                                        <span class="d-none d-md-inline">Carrello</span>
                                    </button>
                                    <button class="btn btn-outline-danger btn-sm" 
                                            onclick="removeFromWishlist(<?php echo $product->id; ?>)"
                                            title="Rimuovi dalla lista desideri">
                                        <i class="bi bi-heart-fill me-1"></i>
                                        <span class="d-none d-md-inline">Rimuovi</span>
                                    </button>
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
                        <a href="category-products.php" class="btn btn-outline-primary-custom">
                            <i class="bi bi-grid me-2"></i>
                            Sfoglia Categorie
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
            
            // Make compatibility check with cart-manager and wishlist manager
        </script>
    </body>
</html>