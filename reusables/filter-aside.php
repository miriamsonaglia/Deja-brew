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

        <!-- Provenienza Filter -->
        <div class="filter-group">
            <h4 class="filter-title">Provenienza</h4>
            <select id="provenienzaFilter" class="form-select">
                <option value="">Tutte le provenienze</option>
                <?php
                // Ottieni tutte le provenienze uniche dai prodotti
                $provenienze = $products->pluck('provenienza')
                                        ->filter()
                                        ->unique()
                                        ->sort()
                                        ->values();

                foreach($provenienze as $provenienza): 
                    if(!empty($provenienza)):
                ?>
                    <option value="<?php echo htmlspecialchars($provenienza);?>">
                        <?php echo htmlspecialchars($provenienza); ?>
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
                    <input type="number" id="minPrice" placeholder="Min €" min="0" step="0.10">
                    <span>-</span>
                    <input type="number" id="maxPrice" placeholder="Max €" min="0" step="0.10">
                </div>
            </div>
        </div>

         <!-- Weight Range Filter -->
        <div class="filter-group">
            <h4 class="filter-title">Peso (gr)</h4>
            <div class="price-range">
                <div class="price-inputs">
                    <input type="number" id="minWeight" placeholder="Min gr" min="0" step="1">
                    <span>-</span>
                    <input type="number" id="maxWeight" placeholder="Max gr" min="0" step="1">
                </div>
            </div>
        </div>
            
        <!-- Sort Options -->
        <div class="filter-group">
            <h4 class="filter-title">Ordina per</h4>
            <select id="sortBy" class="form-select">
                <option value="default">Predefinito</option>
                <option value="name-asc">Nome A-Z</option>
                <option value="name-desc">Nome Z-A</option>
                <option value="price-asc">Prezzo: dal più basso</option>
                <option value="price-desc">Prezzo: dal più alto</option>
            </select>
        </div>
            
        <!-- Filter Actions -->
        <div class="filter-actions">
            <button class="btn-clear-filters" id="clearFilters">
                Pulisci Tutto
            </button>
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