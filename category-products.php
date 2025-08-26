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
            require_once __DIR__ . '/Models/Aroma.php';
            require_once __DIR__ . '/role.php';
            use App\Models\Prodotto;
            use App\Models\Categoria;
            use App\Models\Aroma;
            session_start();
            $userRole = $_SESSION['UserRole'] ?? Role::GUEST;
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
                case Role::GUEST:
                    include('./reusables/navbars/empty-navbar.php');
                    break;
                case Role::BUYER:
                    include('./reusables/navbars/buyer-navbar.php');
                    break;
                case Role::VENDOR:
                    include('./reusables/navbars/vendor-navbar.php');
                    break;
                default:
                    include('./reusables/navbars/buyer-navbar.php');
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
                                data-product-aroma="<?php echo htmlspecialchars($product->aroma ? $product->aroma->gusto : ''); ?>">
                                <img src="<?php echo htmlspecialchars($product->fotografia); ?>" 
                                     alt="<?php echo htmlspecialchars($product->nome); ?>">
                                <div class="product-name"><?php echo htmlspecialchars($product->nome); ?></div>
                                <div class="product-price"><?php echo number_format($product->prezzo, 2); ?> €</div>
                                <?php if(isset($userRole) && ($userRole == Role::BUYER)): ?>
                                <input type="number" 
                                       step="1" 
                                       value="0" 
                                       min="0" 
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
        <!-- Filters Sidebar - VERSIONE SEMPLIFICATA -->
        <aside class="filters-sidebar" id="filtersSidebar">
            <div class="filters-header">
                <h3>Filtri</h3>
                <button class="close-filters-btn" id="closeFiltersBtn">
                    <i class="bi bi-x"></i>
                </button>
            </div>

            <div class="filters-content">
                <!-- Aromi Filter -->
                <div class="filter-group">
                    <h4 class="filter-title">Aroma</h4>
                    <select id="aromaFilter" class="form-select">
                        <option value="">Tutti gli aromi</option>
                        <?php
                        // Ottieni tutti gli aromi unici dai prodotti (usando Collection di Laravel)
                        $aromi = $products->pluck('aroma')
                                         ->filter()
                                         ->unique()
                                         ->sort()
                                         ->values();

                        foreach($aromi as $aroma): 
                            if(!empty($aroma)):
                        ?>
                            <option value="<?php echo htmlspecialchars($aroma); ?>">
                                <?php echo htmlspecialchars($aroma->gusto); ?>
                            </option>
                        <?php 
                            endif;
                        endforeach; 
                        ?>
                    </select>
                </div>
                    
                <!-- Price Range Filter -->
                <div class="filter-group">
                    <h4 class="filter-title">Prezzo</h4>
                    <div class="price-range">
                        <div class="price-inputs">
                            <input type="number" id="minPrice" placeholder="Min €" min="0" step="0.01">
                            <span>-</span>
                            <input type="number" id="maxPrice" placeholder="Max €" min="0" step="0.01">
                        </div>
                    </div>
                </div>
                    
                <!-- Sort Options -->
                <div class="filter-group">
                    <h4 class="filter-title">Ordina per</h4>
                    <select id="sortBy" class="form-select">
                        <option value="default">Predefinito</option>
                        <option value="name-az">Nome A-Z</option>
                        <option value="name-za">Nome Z-A</option>
                        <option value="price-low">Prezzo: dal più basso</option>
                        <option value="price-high">Prezzo: dal più alto</option>
                    </select>
                </div>
                    
                <!-- Filter Actions -->
                <div class="filter-actions">
                    <button class="btn-apply-filters" id="applyFilters">
                        Applica Filtri
                    </button>
                    <button class="btn-clear-filters" id="clearFilters">
                        Pulisci Tutto
                    </button>
                </div>
                    
                <!-- Results Counter -->
                <div class="results-counter">
                    <span id="resultsCount"><?php echo $products->count(); ?> prodotti trovati</span>
                </div>
            </div>
        </aside>

        <!-- Filters Toggle Button -->
        <button class="filters-toggle-btn" id="filtersToggleBtn">
            <i class="bi bi-funnel"></i>
            <span>Filtri</span>
        </button>

        <!-- Overlay for mobile -->
        <div class="filters-overlay" id="filtersOverlay"></div>
        <footer><!-- ?? Possible footer template ?? --></footer>
        
        <!-- INSERT HERE ALL JAVASCRIPT NECESSARY IMPORTS -->
        <script src="./dist/bootstrap5/js/bootstrap.min.js"></script>
        <script src="./dist/custom/js/sidebar-manager.js"></script>
        
        <?php if(isset($userRole) && ($userRole == Role::BUYER)): ?>
            <script src="./dist/custom/js/wishlist-manager.js"></script>
            <script src="./dist/custom/js/cart-manager.js"></script>
            <script src="./dist/custom/js/input-validation.js"></script>
        <?php endif; ?>
    </body>
    
    <script>
        <?php if(isset($userRole) && ($userRole == Role::BUYER)): ?>
        // Cart functionality
        document.addEventListener('DOMContentLoaded', function() {
            let addToCartButtons = document.querySelectorAll(".cart-button");

            addToCartButtons.forEach((button) => {
                button.addEventListener('click', () => {
                    let li = button.closest('li');
                    let productID = button.getAttribute('data-product-id');
                    let numberInput = li.querySelector('input[type=number]');
                    let quantity = numberInput ? parseInt(numberInput.value) : 0;

                    console.log('Aggiungo al carrello: ' + quantity + " di " + productID);

                    // Se quantity è 0 o productID non valido, esci
                    if (quantity <= 0 || !productID) {
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
                        // Reset quantity input after successful add
                        if (numberInput) {
                            numberInput.value = 0;
                        }
                    })
                    .catch(error => {
                        console.error('Errore durante la richiesta:', error);
                    });
                });
            }); 
        });
        <?php endif; ?>

        // Filters functionality
        document.addEventListener('DOMContentLoaded', function() {
            const filtersSidebar = document.getElementById('filtersSidebar');
            const filtersToggleBtn = document.getElementById('filtersToggleBtn');
            const closeFiltersBtn = document.getElementById('closeFiltersBtn');
            const filtersOverlay = document.getElementById('filtersOverlay');
            const applyFiltersBtn = document.getElementById('applyFilters');
            const clearFiltersBtn = document.getElementById('clearFilters');
            
            // Get all products for filtering
            const productGrid = document.querySelector('.product-grid');
            const allProducts = Array.from(productGrid.querySelectorAll('.slider-object'));
            const resultsCounter = document.getElementById('resultsCount');
            
            // Toggle filters sidebar
            function openFilters() {
                filtersSidebar.classList.add('active');
                filtersOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            }
            
            function closeFilters() {
                filtersSidebar.classList.remove('active');
                filtersOverlay.classList.remove('active');
                document.body.style.overflow = '';
            }
            
            // Event listeners
            filtersToggleBtn.addEventListener('click', openFilters);
            closeFiltersBtn.addEventListener('click', closeFilters);
            filtersOverlay.addEventListener('click', closeFilters);
            
            // Filter application
            function applyFilters() {
                const minPrice = parseFloat(document.getElementById('minPrice').value) || 0;
                const maxPrice = parseFloat(document.getElementById('maxPrice').value) || Infinity;
                const inStockOnly = document.getElementById('inStock').checked;
                const sortBy = document.querySelector('input[name="sortBy"]:checked').value;
                const searchTerm = document.getElementById('productSearch').value.toLowerCase();
                
                let filteredProducts = allProducts.filter(product => {
                    // Price filter
                    const priceElement = product.querySelector('.product-price');
                    const price = parseFloat(priceElement.textContent.replace('€', '').replace(',', '.'));
                    
                    if (price < minPrice || price > maxPrice) {
                        return false;
                    }
                    
                    // Search filter
                    const productName = product.querySelector('.product-name').textContent.toLowerCase();
                    if (searchTerm && !productName.includes(searchTerm)) {
                        return false;
                    }
                    
                    return true;
                });
                
                // Sort products
                switch(sortBy) {
                    case 'price-low':
                        filteredProducts.sort((a, b) => {
                            const priceA = parseFloat(a.querySelector('.product-price').textContent.replace('€', '').replace(',', '.'));
                            const priceB = parseFloat(b.querySelector('.product-price').textContent.replace('€', '').replace(',', '.'));
                            return priceA - priceB;
                        });
                        break;
                    case 'price-high':
                        filteredProducts.sort((a, b) => {
                            const priceA = parseFloat(a.querySelector('.product-price').textContent.replace('€', '').replace(',', '.'));
                            const priceB = parseFloat(b.querySelector('.product-price').textContent.replace('€', '').replace(',', '.'));
                            return priceB - priceA;
                        });
                        break;
                    case 'name':
                        filteredProducts.sort((a, b) => {
                            const nameA = a.querySelector('.product-name').textContent;
                            const nameB = b.querySelector('.product-name').textContent;
                            return nameA.localeCompare(nameB);
                        });
                        break;
                }
                
                // Hide all products
                allProducts.forEach(product => {
                    product.style.display = 'none';
                });
                
                // Show filtered products
                filteredProducts.forEach(product => {
                    product.style.display = 'flex';
                });
                
                // Update results counter
                resultsCounter.textContent = `${filteredProducts.length} prodotti trovati`;
                
                // Update price range display
                updatePriceRangeDisplay();
            }
            
            function clearFilters() {
                // Reset all inputs
                document.getElementById('minPrice').value = '';
                document.getElementById('maxPrice').value = '';
                document.getElementById('inStock').checked = true;
                document.querySelector('input[name="sortBy"][value="default"]').checked = true;
                document.getElementById('productSearch').value = '';
                
                // Show all products
                allProducts.forEach(product => {
                    product.style.display = 'flex';
                });
                
                // Update counter
                resultsCounter.textContent = `${allProducts.length} prodotti trovati`;
                updatePriceRangeDisplay();
            }
            
            function updatePriceRangeDisplay() {
                const minPrice = document.getElementById('minPrice').value;
                const maxPrice = document.getElementById('maxPrice').value;
                const priceRangeText = document.getElementById('priceRangeText');
                
                if (!minPrice && !maxPrice) {
                    priceRangeText.textContent = 'Tutti i prezzi';
                } else if (minPrice && maxPrice) {
                    priceRangeText.textContent = `€${minPrice} - €${maxPrice}`;
                } else if (minPrice) {
                    priceRangeText.textContent = `Da €${minPrice}`;
                } else {
                    priceRangeText.textContent = `Fino a €${maxPrice}`;
                }
            }
            
            // Event listeners for filter controls
            applyFiltersBtn.addEventListener('click', applyFilters);
            clearFiltersBtn.addEventListener('click', clearFilters);
            
            // Clear search button
            document.getElementById('clearSearch').addEventListener('click', () => {
                document.getElementById('productSearch').value = '';
                applyFilters();
            });
            
            // Price range inputs
            document.getElementById('minPrice').addEventListener('input', updatePriceRangeDisplay);
            document.getElementById('maxPrice').addEventListener('input', updatePriceRangeDisplay);
            
            // Real-time search (optional)
            document.getElementById('productSearch').addEventListener('input', function() {
                clearTimeout(this.searchTimeout);
                this.searchTimeout = setTimeout(() => applyFilters(), 500);
            });
        });
    </script>
</html>