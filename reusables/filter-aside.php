<!-- Filters Sidebar -->
<aside class="filters-sidebar" id="filtersSidebar">
    <div class="filter-header d-flex justify-content-between align-items-center">
        <h3 class="mb-0">Filtri</h3>
        <button class="btn btn-sm btn-outline-secondary" id="closeFiltersBtn" title="Chiudi filtri">
            <i class="bi bi-x-lg"></i>
        </button>
    </div>

    <div class="filters-content">
        <!-- Aromi Filter -->
        <div class="filter-section">
            <h4 class="filter-title">Aroma</h4>
            <select id="aromaFilter" class="form-select">
                <option value="">Tutti gli aromi</option>
                <?php
                $aromi = $products->pluck('aroma')
                                  ->filter()
                                  ->unique('gusto')
                                  ->sortBy('gusto')
                                  ->values();

                foreach ($aromi as $aroma): 
                    if (!empty($aroma)):
                ?>
                    <option value="<?php echo htmlspecialchars($aroma->gusto); ?>">
                        <?php echo htmlspecialchars($aroma->gusto); ?>
                    </option>
                <?php 
                    endif;
                endforeach;
                ?>
            </select>
        </div>

        <!-- Provenienza Filter -->
        <div class="filter-section">
            <h4 class="filter-title">Provenienza</h4>
            <select id="provenienzaFilter" class="form-select">
                <option value="">Tutte le provenienze</option>
                <?php
                $provenienze = $products->pluck('provenienza')
                                        ->filter()
                                        ->unique()
                                        ->sort()
                                        ->values();

                foreach ($provenienze as $provenienza): 
                    if (!empty($provenienza)):
                ?>
                    <option value="<?php echo htmlspecialchars($provenienza); ?>">
                        <?php echo htmlspecialchars($provenienza); ?>
                    </option>
                <?php 
                    endif;
                endforeach;
                ?>
            </select>
        </div>

        <!-- Price Range Filter -->
        <div class="filter-section">
            <h4 class="filter-title">Prezzo</h4>
            <div class="price-range d-flex gap-2 align-items-center">
                <input type="number" id="minPrice" class="form-control" placeholder="Min €" min="0" step="0.10">
                <span>-</span>
                <input type="number" id="maxPrice" class="form-control" placeholder="Max €" min="0" step="0.10">
            </div>
        </div>

        <!-- Weight Range Filter -->
        <div class="filter-section">
            <h4 class="filter-title">Peso (gr)</h4>
            <div class="price-range d-flex gap-2 align-items-center">
                <input type="number" id="minWeight" class="form-control" placeholder="Min gr" min="0" step="1">
                <span>-</span>
                <input type="number" id="maxWeight" class="form-control" placeholder="Max gr" min="0" step="1">
            </div>
        </div>

        <!-- Sort Options -->
        <div class="filter-section">
            <h4 class="filter-title">Ordina per</h4>
            <select id="sortBy" class="form-select">
                <option value="default" default>Predefinito</option>
                <option value="name-asc">Nome A-Z</option>
                <option value="name-desc">Nome Z-A</option>
                <option value="price-asc">Prezzo: dal più basso</option>
                <option value="price-desc">Prezzo: dal più alto</option>
            </select>
        </div>

        <!-- Clear Filters Button -->
        <div class="filter-section text-center">
            <button class="btn btn-outline-primary-custom w-100 mt-3" id="clearFilters">
                <i class="bi bi-x-circle me-1"></i> Pulisci Tutto
            </button>
        </div>
    </div>
</aside>

<!-- Filters Toggle Button -->
<button class="filters-toggle" id="filtersToggleBtn" title="Mostra filtri">
    <i class="bi bi-funnel fs-4"></i>
</button>

<!-- Overlay for mobile -->
<div class="filters-overlay" id="filtersOverlay"></div>
