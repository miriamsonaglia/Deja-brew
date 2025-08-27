class Filter {
    constructor() {
        this.filtersSidebar = document.getElementById('filtersSidebar');
        this.filtersToggleBtn = document.getElementById('filtersToggleBtn');
        this.closeFiltersBtn = document.getElementById('closeFiltersBtn');
        this.filtersOverlay = document.getElementById('filtersOverlay');
        this.applyFiltersBtn = document.getElementById('applyFilters');
        this.clearFiltersBtn = document.getElementById('clearFilters');
        this.elements = document.querySelectorAll('.slider-object');
        this.maxPriceFilter = document.getElementById('maxPrice');
        this.minPriceFilter = document.getElementById('minPrice');
        this.aromaFilter = document.getElementById('aromaFilter');
        this.provenienzaFilter = document.getElementById('provenienzaFilter');
        this.minWeightFilter = document.getElementById('minWeight');
        this.maxWeightFilter = document.getElementById('maxWeight');
        this.sortBy = document.getElementById('sortBy');
        this.init();
    }

    init() {
         // Event listeners
        this.filtersToggleBtn.addEventListener('click', () => this.openFilters());
        this.closeFiltersBtn.addEventListener('click', () => this.closeFilters());
        this.filtersOverlay.addEventListener('click', () => this.closeFilters());
        this.maxPriceFilter.addEventListener('input', () => this.applyFilters());
        this.minPriceFilter.addEventListener('input', () => this.applyFilters());
        this.aromaFilter.addEventListener('change', () => this.applyFilters());
        this.provenienzaFilter.addEventListener('change', () => this.applyFilters());
        this.minWeightFilter.addEventListener('input', () => this.applyFilters());
        this.maxWeightFilter.addEventListener('input', () => this.applyFilters());
        this.sortBy.addEventListener('change', () => this.sort());
        this.clearFiltersBtn.addEventListener('click', () => this.clearAllFilters());
    }

    // Toggle filters sidebar
    openFilters() {
        this.filtersSidebar.classList.add('active');
        this.filtersOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    closeFilters() {
        this.filtersSidebar.classList.remove('active');
        this.filtersOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    applyFilters() {
        const maxPrice = parseFloat(this.maxPriceFilter.value);
        const minPrice = parseFloat(this.minPriceFilter.value);
        const selectedAroma = this.aromaFilter.value ? JSON.parse(this.aromaFilter.value).gusto : '';
        const selectedProvenienza = this.provenienzaFilter.value;
        const maxWeight = parseFloat(this.maxWeightFilter.value);
        const minWeight = parseFloat(this.minWeightFilter.value);

        this.elements.forEach(element => {
            const price = parseFloat(element.getAttribute('data-product-price'));
            const aroma = element.getAttribute('data-product-aroma');
            const provenienza = element.getAttribute('data-product-provenienza');
            const weight = parseFloat(element.getAttribute('data-product-weight'));
            
            // Price filter
            let matchesPrice;
            if(!isNaN(minPrice) && !isNaN(maxPrice)) {
                matchesPrice = (price >= minPrice) && (price <= maxPrice);
            } else if(!isNaN(minPrice)) {
                matchesPrice = price >= minPrice;
            } else if(!isNaN(maxPrice)) {
                matchesPrice = price <= maxPrice;
            } else {
                matchesPrice = true;
            }

            // Aroma filter
            const matchesAroma = selectedAroma === '' || aroma === selectedAroma;
            
            // Provenienza filter
            const matchesProvenienza = selectedProvenienza === '' || provenienza === selectedProvenienza;
            
            // Weight filter
            let matchesWeight;
            if(!isNaN(minWeight) && !isNaN(maxWeight)) {
                matchesWeight = (weight >= minWeight) && (weight <= maxWeight);
            } else if(!isNaN(minWeight)) {
                matchesWeight = weight >= minWeight;
            } else if(!isNaN(maxWeight)) {
                matchesWeight = weight <= maxWeight;
            } else {
                matchesWeight = true;
            }

            // Show/hide element based on all filters
            if (matchesPrice && matchesAroma && matchesProvenienza && matchesWeight) {
                element.style.display = '';
            } else {
                element.style.display = 'none';
            }
        });
    }

    sort() {
        const sortValue = this.sortBy.value;
        if (sortValue === 'price-asc') {
            this.sortByPrice(true);
        } else if (sortValue === 'price-desc') {
            this.sortByPrice(false);
        } else if (sortValue === 'name-asc') {
            this.sortByName(true);
        } else if (sortValue === 'name-desc') {
            this.sortByName(false);
        }
    }

    sortByPrice(ascending = true) {
        const container = document.querySelector('.product-grid');
        const productsArray = Array.from(this.elements);

        productsArray.sort((a, b) => {
            const priceA = parseFloat(a.getAttribute('data-product-price'));
            const priceB = parseFloat(b.getAttribute('data-product-price'));
            return ascending ? priceA - priceB : priceB - priceA;
        });

        // SOLUZIONE MIGLIORE: Riordina senza distruggere gli elementi
        const fragment = document.createDocumentFragment();
        productsArray.forEach(product => {
            fragment.appendChild(product); // Sposta l'elemento senza clonarlo
        });
        container.appendChild(fragment);
        
        // Aggiorna la reference agli elementi
        this.elements = document.querySelectorAll('.slider-object');
    }
    
    clearAllFilters() {
        // Reset tutti i filtri ai valori di default
        this.maxPriceFilter.value = '';
        this.minPriceFilter.value = '';
        this.aromaFilter.value = '';
        this.provenienzaFilter.value = '';
        this.maxWeightFilter.value = '';
        this.minWeightFilter.value = '';
        this.sortBy.value = 'default';
        
        // Mostra tutti gli elementi
        this.elements.forEach(element => {
            element.style.display = '';
        });
        
        // Ripristina l'ordine originale se necessario
        if (this.sortBy.value === 'default') {
            this.restoreOriginalOrder();
        }
    }
    
    restoreOriginalOrder() {
        // Ripristina l'ordine originale basato sull'ordine nel DOM originale
        // Questo metodo assume che gli elementi siano già nell'ordine originale quando la pagina viene caricata
        const container = document.querySelector('.product-grid');
        const originalElements = Array.from(document.querySelectorAll('.slider-object'));
        
        // Ordina gli elementi secondo il loro ordine originale nel DOM
        // (questo funziona se gli elementi sono già nell'ordine corretto al caricamento)
        const fragment = document.createDocumentFragment();
        originalElements.forEach(element => {
            fragment.appendChild(element);
        });
        container.appendChild(fragment);
        
        // Aggiorna la reference
        this.elements = document.querySelectorAll('.slider-object');
    }

    sortByName(ascending = true) {
        const container = document.querySelector('.product-grid');
        const productsArray = Array.from(this.elements);

        productsArray.sort((a, b) => {
            const nameA = a.getAttribute('data-product-name').toLowerCase();
            const nameB = b.getAttribute('data-product-name').toLowerCase();
            if (nameA < nameB) return ascending ? -1 : 1;
            if (nameA > nameB) return ascending ? 1 : -1;
            return 0;
        });

        // SOLUZIONE MIGLIORE: Riordina senza distruggere gli elementi
        const fragment = document.createDocumentFragment();
        productsArray.forEach(product => {
            fragment.appendChild(product); // Sposta l'elemento senza clonarlo
        });
        container.appendChild(fragment);
        
        // Aggiorna la reference agli elementi
        this.elements = document.querySelectorAll('.slider-object');
    }
}