class Filter {
    constructor() {
        this.cacheDOM();
        this.bindEvents();
        this.originalOrder = Array.from(this.elements);
    }

    cacheDOM() {
        this.filtersSidebar = document.getElementById('filtersSidebar');
        this.filtersToggleBtn = document.getElementById('filtersToggleBtn');
        this.closeFiltersBtn = document.getElementById('closeFiltersBtn');
        this.filtersOverlay = document.getElementById('filtersOverlay');
        this.clearFiltersBtn = document.getElementById('clearFilters');
        this.elements = document.querySelectorAll('.slider-object');

        this.filters = {
            aroma: document.getElementById('aromaFilter'),
            provenienza: document.getElementById('provenienzaFilter'),
            minPrice: document.getElementById('minPrice'),
            maxPrice: document.getElementById('maxPrice'),
            minWeight: document.getElementById('minWeight'),
            maxWeight: document.getElementById('maxWeight'),
            sortBy: document.getElementById('sortBy')
        };
    }

    bindEvents() {
        this.filtersToggleBtn.addEventListener('click', () => this.openSidebar());
        this.closeFiltersBtn.addEventListener('click', () => this.closeSidebar());
        this.filtersOverlay.addEventListener('click', () => this.closeSidebar());
        this.clearFiltersBtn.addEventListener('click', () => this.clearAllFilters());

        Object.values(this.filters).forEach(input => {
            input.addEventListener('input', () => {
                if (input === this.filters.sortBy) {
                    this.sort();
                } else {
                    this.applyFilters();
                }
            });
        });
    }

    openSidebar() {
        this.filtersSidebar.classList.add('show');
        this.filtersOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }

    closeSidebar() {
        this.filtersSidebar.classList.remove('show');
        this.filtersOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }

    applyFilters() {
        const {
            aroma, provenienza,
            minPrice, maxPrice,
            minWeight, maxWeight
        } = this.filters;

        const filters = {
            aroma: aroma.value,
            provenienza: provenienza.value,
            minPrice: parseFloat(minPrice.value),
            maxPrice: parseFloat(maxPrice.value),
            minWeight: parseFloat(minWeight.value),
            maxWeight: parseFloat(maxWeight.value)
        };

        this.elements.forEach(el => {
            const price = parseFloat(el.dataset.productPrice);
            const weight = parseFloat(el.dataset.productWeight);
            const aromaVal = el.dataset.productAroma;
            const provenienzaVal = el.dataset.productProvenienza;

            const matchesPrice = (
                (isNaN(filters.minPrice) || price >= filters.minPrice) &&
                (isNaN(filters.maxPrice) || price <= filters.maxPrice)
            );

            const matchesWeight = (
                (isNaN(filters.minWeight) || weight >= filters.minWeight) &&
                (isNaN(filters.maxWeight) || weight <= filters.maxWeight)
            );

            const matchesAroma = !filters.aroma || aromaVal === filters.aroma;
            const matchesProvenienza = !filters.provenienza || provenienzaVal === filters.provenienza;

            el.style.display = (matchesPrice && matchesWeight && matchesAroma && matchesProvenienza)
                ? ''
                : 'none';
        });
    }

    sort() {
        const sortValue = this.filters.sortBy.value;
        const container = document.querySelector('.product-grid');
        const productsArray = Array.from(this.elements);

        const sortFn = {
            'name-asc': (a, b) =>
                a.dataset.productName.localeCompare(b.dataset.productName),
            'name-desc': (a, b) =>
                b.dataset.productName.localeCompare(a.dataset.productName),
            'price-asc': (a, b) =>
                parseFloat(a.dataset.productPrice) - parseFloat(b.dataset.productPrice),
            'price-desc': (a, b) =>
                parseFloat(b.dataset.productPrice) - parseFloat(a.dataset.productPrice)
        }[sortValue];

        if (sortFn) {
            productsArray.sort(sortFn);
            const fragment = document.createDocumentFragment();
            productsArray.forEach(product => fragment.appendChild(product));
            container.appendChild(fragment);
            this.elements = document.querySelectorAll('.slider-object');
        } else {
            this.restoreOriginalOrder();
        }
    }

    clearAllFilters() {
        Object.values(this.filters).forEach(input => {
            input.value = '';
        });
        this.filters.sortBy.value = 'default';

        this.restoreOriginalOrder();

        this.elements.forEach(el => {
            el.style.display = '';
        });
    }

    restoreOriginalOrder() {
        const container = document.querySelector('.product-grid');
        const fragment = document.createDocumentFragment();
        this.originalOrder.forEach(el => fragment.appendChild(el));
        container.appendChild(fragment);
        this.elements = document.querySelectorAll('.slider-object');
    }
}
