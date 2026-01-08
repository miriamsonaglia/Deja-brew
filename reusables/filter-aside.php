<!-- Filters Sidebar -->
<aside class="filters-sidebar" id="filtersSidebar" role="complementary" aria-label="Filtri di ricerca prodotti">
    <div class="filter-header d-flex justify-content-between align-items-center border-bottom pb-3 mb-3" style="background-color: #7C2E2E;">
        <h3 class="mb-0 fw-bold" style="color: #ffffff; font-size: 1.5rem;">Filtri</h3>
        <button class="btn btn-sm btn-close btn-close-white" id="closeFiltersBtn" title="Chiudi filtri" aria-label="Chiudi pannello filtri" aria-expanded="true"></button>
    </div>

    <div class="filters-content">
        <!-- Aromi Filter -->
        <div class="filter-section mb-4">
            <h5 class="filter-title fw-bold text-primary-brown mb-2">Aroma</h5>
            <select id="aromaFilter" class="form-select form-control-custom" aria-label="Filtra per aroma">
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
        <div class="filter-section mb-4">
            <h5 class="filter-title fw-bold text-primary-brown mb-2">Provenienza</h5>
            <select id="provenienzaFilter" class="form-select form-control-custom" aria-label="Filtra per provenienza">
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
        <div class="filter-section mb-4">
            <h5 class="filter-title fw-bold text-primary-brown mb-2">Prezzo</h5>
            <div class="price-range d-flex gap-2 align-items-center">
                <input type="number" id="minPrice" class="form-control form-control-custom" placeholder="Min €" min="0" step="0.10" aria-label="Prezzo minimo">
                <span class="text-muted">-</span>
                <input type="number" id="maxPrice" class="form-control form-control-custom" placeholder="Max €" min="0" step="0.10" aria-label="Prezzo massimo">
            </div>
        </div>

        <!-- Weight Range Filter -->
        <div class="filter-section mb-4">
            <h5 class="filter-title fw-bold text-primary-brown mb-2">Peso (gr)</h5>
            <div class="price-range d-flex gap-2 align-items-center">
                <input type="number" id="minWeight" class="form-control form-control-custom" placeholder="Min gr" min="0" step="1" aria-label="Peso minimo (grammi)">
                <span class="text-muted">-</span>
                <input type="number" id="maxWeight" class="form-control form-control-custom" placeholder="Max gr" min="0" step="1" aria-label="Peso massimo (grammi)">
            </div>
        </div>

        <!-- Sort Options -->
        <div class="filter-section mb-4">
            <h5 class="filter-title fw-bold text-primary-brown mb-2">Ordina per</h5>
            <select id="sortBy" class="form-select form-control-custom" aria-label="Ordina risultati per">
                <option value="default" default>Predefinito</option>
                <option value="name-asc">Nome A-Z</option>
                <option value="name-desc">Nome Z-A</option>
                <option value="price-asc">Prezzo: dal più basso</option>
                <option value="price-desc">Prezzo: dal più alto</option>
            </select>
        </div>

        <!-- Clear Filters Button -->
        <div class="filter-section text-center">
            <button class="btn btn-primary-custom w-100 mt-3" id="clearFilters" aria-label="Cancella tutti i filtri applicati">
                <i class="bi bi-x-circle me-1"></i> Pulisci Tutto
            </button>
        </div>
    </div>
</aside>

<!-- Filters Toggle Button -->
<button class="filters-toggle" id="filtersToggleBtn" title="Mostra filtri" aria-label="Apri pannello filtri" aria-expanded="false" aria-controls="filtersSidebar">
    <i class="bi bi-funnel fs-4"></i>
</button>

<!-- Overlay for mobile -->
<div class="filters-overlay" id="filtersOverlay"></div>
