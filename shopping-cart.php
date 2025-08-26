<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Carrello - Deja-brew</title>
        <!-- INSERT HERE ALL CSS NECESSARY IMPORTS -->
        <link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
        <link rel="stylesheet" href="./dist/bootstrap5/icons/bootstrap-icons.css">
        <link rel="stylesheet" href="./dist/custom/css/style.css">

        <?php
            // This in-page script initialize all the required php imports and populates the datasets.
            use App\Models\Prodotto;
            use App\Models\Lista;

            require_once __DIR__ . '/bootstrap.php';
            require_once __DIR__ . '/Models/UtenteCompratore.php';
            require_once __DIR__ . '/Models/Prodotto.php';
            require_once __DIR__ . '/Models/Lista.php';
            require_once __DIR__ . '/role.php';
            session_start();
            
            // QUERY: READ ALL USER SHOPPING CART FROM DATABASE
            if(!isset($_SESSION['LoggedUser']['id']) || $_SESSION['UserRole'] != Role::BUYER->value){
                header("Location: login.php");
                exit;
            }
            
            $user_cart = Lista::where('id_utente_compratore', $_SESSION['LoggedUser']['id'])
                            ->where('tipo', 'carrello')
                            ->get();
            
            $total = 0;
            $item_count = 0;
        ?>
    </head>
    <body class="cart-page">
        <header><!-- ?? Possible header template ?? --></header>
        <?php include('./reusables/navbars/buyer-navbar.php'); ?>
        
            <?php if(count($user_cart) > 0): ?>
            <div class="cart-content">
                <!-- Cart Items -->
                <div class="cart-items">
                    <div class="cart-items-header">
                        <i class="bi bi-bag-check"></i> 
                        Prodotti nel carrello (<span id="cart-items-count"><?php echo count($user_cart); ?></span> articoli)
                    </div>
                    
                    <?php 
                    foreach($user_cart as $cart_item):
                        $product = Prodotto::where('id', $cart_item->id_prodotto)->first();
                        if($product):
                            $subtotal = floatval($product->prezzo) * intval($cart_item->quantita);
                            $total += $subtotal;
                            $item_count += intval($cart_item->quantita);
                    ?>
                    <div class="cart-item" data-product-id="<?php echo $product->id; ?>">
                        <img src="<?php echo htmlspecialchars($product->fotografia); ?>" 
                             alt="<?php echo htmlspecialchars($product->nome); ?>" 
                             class="cart-product-image">
                        
                        <div class="cart-product-info">
                            <a href="product.php?id=<?php echo $product->id; ?>" class="cart-product-name">
                                <?php echo htmlspecialchars($product->nome); ?>
                            </a>
                            <div class="cart-product-price">
                                € <?php echo number_format($product->prezzo, 2); ?> cad.
                            </div>
                        </div>
                        
                        <div class="cart-quantity-controls">
                            <button class="cart-quantity-btn" onclick="updateCartQuantity(<?php echo $product->id; ?>, -1)">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" 
                                   value="<?php echo $cart_item->quantita; ?>" 
                                   min="1" 
                                   class="cart-quantity-input" 
                                   data-product-id="<?php echo $product->id; ?>"
                                   onchange="updateCartQuantityInput(<?php echo $product->id; ?>, this.value)">
                            <button class="cart-quantity-btn" onclick="updateCartQuantity(<?php echo $product->id; ?>, 1)">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        
                        <div class="cart-subtotal-price" data-product-id="<?php echo $product->id; ?>">
                            € <?php echo number_format($subtotal, 2); ?>
                        </div>
                        
                        <button class="cart-remove-btn" onclick="removeCartItem(<?php echo $product->id; ?>)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                    <?php 
                        endif;
                    endforeach; 
                    ?>
                </div>

                <!-- Cart Summary -->
                <div class="cart-summary">
                    <h2 class="summary-header">
                        Riepilogo Ordine
                    </h2>
                    
                    <div class="summary-row">
                        <span class="summary-label">Subtotale:</span>
                        <span class="summary-value" id="cart-subtotal">€ <?php echo number_format($total, 2); ?></span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="summary-label">Spedizione:</span>
                        <span class="summary-value" id="shipping-cost">€ 5.90</span>
                    </div>
                    
                    <div class="summary-row">
                        <span class="summary-label">IVA (22%):</span>
                        <span class="summary-value" id="tax-amount">€ <?php echo number_format($total * 0.22, 2); ?></span>
                    </div>
                    
                    <div class="summary-row summary-total-row">
                        <span class="summary-label">Totale:</span>
                        <span class="summary-value" id="cart-total">€ <?php echo number_format($total + 5.90 + ($total * 0.22), 2); ?></span>
                    </div>
                    
                    <div class="delivery-info">
                        <div class="delivery-title">
                            <i class="bi bi-truck"></i> Informazioni Consegna
                        </div>
                        <div class="delivery-details">
                            <strong>Consegna prevista:</strong> 2-3 giorni lavorativi<br>
                            <strong>Indirizzo:</strong> Da definire al checkout
                        </div>
                    </div>
                    
                    <a href="checkout.php" class="checkout-btn">
                        <i class="bi bi-credit-card"></i>
                        Procedi all'Acquisto
                    </a>
                    
                    <a href="home.php" class="continue-shopping-btn">
                        <i class="bi bi-arrow-left"></i>
                        Continua gli Acquisti
                    </a>
                </div>
            </div>
            
            <?php else: ?>
            <!-- Empty Cart State -->
            <div class="empty-cart">
                <i class="bi bi-cart-x"></i>
                <h3>Il tuo carrello è vuoto</h3>
                <p>Aggiungi alcuni prodotti per iniziare i tuoi acquisti!</p>
                <a href="home.php" class="continue-shopping-btn" style="width: auto; margin-top: 0;">
                    <i class="bi bi-shop"></i>
                    Inizia lo Shopping
                </a>
            </div>
            <?php endif; ?>
        </div>

        <footer><!-- ?? Possible footer template ?? --></footer>
        
        <!-- INSERT HERE ALL JAVASCRIPT NECESSARY IMPORTS -->
        <script src="./dist/bootstrap5/js/bootstrap.min.js"></script>
        <script src="./dist/custom/js/cart-manager.js"></script>

        <script>
            // Initialize everything when DOM is ready
            document.addEventListener('DOMContentLoaded', function() {
                updateCartCount();
            });
        </script>
    </body>
</html>