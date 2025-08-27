<!-- Filters Sidebar -->
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
                    <option value="<?php echo htmlspecialchars($aroma);?>">
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