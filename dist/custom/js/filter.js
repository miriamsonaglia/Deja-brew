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
    }

    // Toggle filters sidebar
    openFilters() {
        filtersSidebar.classList.add('active');
        filtersOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    closeFilters() {
        filtersSidebar.classList.remove('active');
        filtersOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    applyFilters() {
        const maxPrice = parseFloat(this.maxPriceFilter.value);
        const minPrice = parseFloat(this.minPriceFilter.value);
        const selectedAroma = this.aromaFilter.value ? JSON.parse(this.aromaFilter.value).gusto : '';

        this.elements.forEach(element => {
            const price = parseFloat(element.getAttribute('data-product-price'));
            const aroma = element.getAttribute('data-product-aroma');
            let matchesPrice;
            if(!isNaN(minPrice) && !isNaN(maxPrice)) {
                matchesPrice = (price >= minPrice) && (price <= maxPrice);
            } else if(!isNaN(minPrice)) {
                matchesPrice = price >= minPrice;
            } else if(!isNaN(maxPrice)) {
                matchesPrice = price <= maxPrice;
            } else {
                matchesPrice = true; // No price filter applied
            }

            const matchesAroma = selectedAroma === '' || aroma === selectedAroma;

            if (matchesPrice && matchesAroma) {
                element.style.display = '';
            } else {
                element.style.display = 'none';
            }
        });
    }

    sortByPrice(ascending = true) {
        const container = document.querySelector('.product-grid-container');
        const productsArray = Array.from(this.elements);

        productsArray.sort((a, b) => {
            const priceA = parseFloat(a.getAttribute('data-price'));
            const priceB = parseFloat(b.getAttribute('data-price'));
            return ascending ? priceA - priceB : priceB - priceA;
        });

        // Clear the container and re-append sorted elements
        container.innerHTML = '';
        productsArray.forEach(product => container.appendChild(product));
    }

    sortByName(ascending = true) {
        const container = document.querySelector('.product-grid-container');
        const productsArray = Array.from(this.elements);

        productsArray.sort((a, b) => {
            const nameA = a.getAttribute('data-product-name').toLowerCase();
            const nameB = b.getAttribute('data-product-name').toLowerCase();
            if (nameA < nameB) return ascending ? -1 : 1;
            if (nameA > nameB) return ascending ? 1 : -1;
            return 0;
        });

        // Clear the container and re-append sorted elements
        container.innerHTML = '';
        productsArray.forEach(product => container.appendChild(product));
    }
}