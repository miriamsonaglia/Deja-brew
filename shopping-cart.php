<!DOCTYPE html>
<html lang="it">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Carrello - Deja-brew</title>
        <!-- Bootstrap 5 CSS -->
        <link rel="stylesheet" href="./dist/bootstrap5/css/bootstrap.min.css">
        <link rel="stylesheet" href="./dist/bootstrap5/icons/bootstrap-icons.css">
        <link rel="stylesheet" href="./dist/custom/css/new-style.css">

        <?php
            // PHP initialization code remains the same
            use App\Models\Prodotto;
            use App\Models\Lista;
            use App\Models\UtenteCompratore;
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
            $user_cart = Lista::where('id_utente_compratore', $utenteCompratore->id)
                            ->where('tipo', 'carrello')
                            ->get();
            
            $total = 0;
            $item_count = 0;
        ?>
    </head>
    <body class="bg-cream">
        <header></header>
        <?php include('./reusables/navbars/buyer-navbar.php'); ?>
        
        <div class="container-fluid" id="main-container">
            <?php if(count($user_cart) > 0): ?>
            <div class="cart-content">
                <div class="row">
                    <!-- Cart Items Column -->
                    <div class="col-lg-8 mb-4">
                        <div class="cart-items-section">
                            <div class="cart-header d-flex align-items-center">
                                <i class="bi bi-bag-check me-2"></i>
                                <span>Prodotti nel carrello (<span id="cart-items-count"><?php echo count($user_cart); ?></span> articoli)</span>
                            </div>
                            
                            <?php 
                            foreach($user_cart as $cart_item):
                                $product = Prodotto::where('id', $cart_item->id_prodotto)->first();
                                if($product):
                                    $subtotal = floatval($product->prezzo) * intval($cart_item->quantita);
                                    $total += $subtotal;
                                    $item_count += intval($cart_item->quantita);
                            ?>
                            <div class="cart-item" data-product-id="<?php echo $product->id;?>">
                                <div class="row align-items-center">
                                    <!-- Product Image -->
                            <div class="col-md-2 col-sm-3">
                                <img src="<?php echo htmlspecialchars(empty($prodotto->fotografia) ? './images/products/Standard_Blend.png' : $prodotto->fotografia); ?>" 
                                     alt="<?php echo htmlspecialchars($product->nome); ?>" 
                                     class="wishlist-product-image img-fluid rounded">
                            </div>
                            
                            <!-- Product Info -->
                            <div class="col-md-4 col-sm-5">
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
                                    
                                    <div class="col-md-3 col-sm-2">
                                        <div class="d-flex align-items-center justify-content-center">
                                            <button class="cart-quantity-btn btn decrease-btn" 
                                                    onclick="decreaseQuantity(<?php echo $product->id;?>)">
                                                <i class="bi bi-dash"></i>
                                            </button>
                                            <!-- Quantity Input: Link onchange with cart logic -->
                                            <input type="text" 
                                                   value="<?php echo $cart_item->quantita; ?>" 
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
                                    
                                    <!-- Subtotal -->
                                    <div class="col-md-2 col-sm-1 text-center">
                                        <div class="fw-bold text-secondary-red cart-subtotal" 
                                             data-subtotal-id="<?php echo $product->id;?>">
                                            € <?php echo number_format($subtotal, 2); ?>
                                        </div>
                                    </div>

                                    <!-- Remove Button -->
                                    <div class="col-md-1 col-sm-1 text-end">
                                        <button class="btn btn-outline-danger btn-sm" 
                                                onclick="removeFromCart(<?php echo $product->id;?>)"
                                                title="Rimuovi dal carrello">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <?php 
                                endif;
                            endforeach; 
                            ?>
                        </div>
                    </div>

                    <!-- Cart Summary Column -->
                    <div class="col-lg-4">
                        <div class="cart-summary position-sticky" style="top: 2rem;">
                            <h3 class="text-primary-brown mb-4 pb-3 border-bottom border-2">
                                <i class="bi bi-receipt me-2"></i>Riepilogo Ordine
                            </h3>
                            
                            <div class="summary-row d-flex justify-content-between align-items-center">
                                <span class="text-muted">Subtotale:</span>
                                <span class="fw-bold text-secondary-red" id="cart-subtotal">
                                    € <?php echo number_format($total, 2); ?>
                                </span>
                            </div>
                            
                            <div class="summary-row d-flex justify-content-between align-items-center">
                                <span class="text-muted">Spedizione:</span>
                                <span class="fw-bold text-secondary-red" id="shipping-cost">
                                    € 5.90
                                </span>
                            </div>
                            
                            <div class="summary-row d-flex justify-content-between align-items-center">
                                <span class="text-muted">IVA (22%):</span>
                                <span class="fw-bold text-secondary-red" id="tax-amount">
                                    € <?php echo number_format($total * 0.22, 2); ?>
                                </span>
                            </div>
                            
                            <div class="summary-total d-flex justify-content-between align-items-center">
                                <span class="fw-bold text-primary-brown">Totale:</span>
                                <span class="fw-bold fs-4 text-secondary-red" id="cart-total">
                                    € <?php echo number_format($total + 5.90 + ($total * 0.22), 2); ?>
                                </span>
                            </div>
                            
                            <!-- Delivery Info -->
                            <div class="bg-light rounded p-3 my-4">
                                <div class="fw-bold text-primary-brown mb-2">
                                    <i class="bi bi-truck me-2"></i>Informazioni Consegna
                                </div>
                                <div class="small text-muted">
                                    <strong>Consegna prevista:</strong> 2-3 giorni lavorativi<br>
                                    <strong>Indirizzo:</strong> Da definire al checkout
                                </div>
                            </div>
                            
                            <!-- Action Buttons -->
                            <div class="d-grid gap-2">
                                <a href="checkout.php" class="btn btn-primary-custom btn-lg">
                                    <i class="bi bi-credit-card me-2"></i>
                                    Procedi all'Acquisto
                                </a>
                                
                                <a href="home.php" class="btn btn-outline-primary-custom">
                                    <i class="bi bi-arrow-left me-2"></i>
                                    Continua gli Acquisti
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <?php else: ?>
            <!-- Empty Cart State -->
            <div class="empty-state">
                <i class="bi bi-cart-x display-1 text-muted mb-4"></i>
                <h3 class="text-primary-brown mb-3">Il tuo carrello è vuoto</h3>
                <p class="text-muted mb-4">Aggiungi alcuni prodotti per iniziare i tuoi acquisti!</p>
                <a href="home.php" class="btn btn-primary-custom">
                    <i class="bi bi-shop me-2"></i>
                    Inizia lo Shopping
                </a>
            </div>
            <?php endif; ?>
        </div>

        <footer></footer>
        
        <!-- Bootstrap 5 JS -->
        <script src="./dist/bootstrap5/js/bootstrap.min.js"></script>
        <script src="./dist/custom/js/cart-manager.js"></script>

        <script>
            updateCartCount();
            const SHIPPING_COST = 5.90;
            const TAX_RATE = 0.22;

            function increaseQuantity(productId) {
                const input = document.querySelector(`input[data-product-id='${productId}']`);
                let currentValue = parseInt(input.value);
                input.value = currentValue + 1;
            
                updateCartQuantity(productId, 1); // Esistente
                updatePrices(productId, currentValue + 1);
            }

            function decreaseQuantity(productId) {
                const input = document.querySelector(`input[data-product-id='${productId}']`);
                let currentValue = parseInt(input.value);
                if (currentValue > 1) {
                    input.value = currentValue - 1;
                
                    updateCartQuantity(productId, -1); // Esistente
                    updatePrices(productId, currentValue - 1);
                } else {
                    // Opzionale: Rimuovi il prodotto se la quantità è 1 e si tenta di diminuire
                    removeFromCart(productId);
                }
            }

            function updatePrices(productId, newQuantity) {
                // Prendi il prezzo unitario dal DOM
                const productContainer = document.querySelector(`input[data-product-id='${productId}']`).closest('.cart-item');
                const priceElement = productContainer.querySelector("small[data-price]");
                const unitPrice = parseFloat(priceElement.getAttribute("data-price"));
            
                // Calcola nuovo subtotale
                const newSubtotal = unitPrice * newQuantity;
            
                // Aggiorna il DOM del subtotale per il singolo prodotto
                const subtotalElement = document.querySelector(`[data-subtotal-id='${productId}']`);
                subtotalElement.innerText = '€ ' + newSubtotal.toFixed(2);
            
                // Ricalcola tutto il carrello
                recalculateCartTotals();
            }

            function recalculateCartTotals() {
                let subtotal = 0;
            
                // Per ogni subtotale nel carrello
                document.querySelectorAll('.cart-subtotal').forEach(item => {
                    const priceText = item.innerText.replace('€', '').replace(',', '.').trim();
                    const amount = parseFloat(priceText);
                    subtotal += amount;
                });
            
                const tax = subtotal * TAX_RATE;
                const total = subtotal + SHIPPING_COST + tax;
            
                // Aggiorna i valori nel DOM
                document.getElementById('cart-subtotal').innerText = '€ ' + subtotal.toFixed(2);
                document.getElementById('tax-amount').innerText = '€ ' + tax.toFixed(2);
                document.getElementById('cart-total').innerText = '€ ' + total.toFixed(2);
            }
        </script>
    </body>
</html>