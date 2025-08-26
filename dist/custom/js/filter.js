class Filter {
    constructor(target) {
        this.elements = document.querySelectorAll(target);
        this.maxPriceFilter = document.getElementById('maxPrice');
        this.minPriceFilter = document.getElementById('minPrice');
        this.aromaFilter = document.getElementById('aromaSelect');
        this.init();
    }
    init() {
        this.maxPriceFilter.addEventListener('input', () => this.applyFilters());
        this.minPriceFilter.addEventListener('input', () => this.applyFilters());
        this.aromaFilter.addEventListener('change', () => this.applyFilters());
    }
    applyFilters() {
        const maxPrice = parseFloat(this.maxPriceFilter.value);
        const minPrice = parseFloat(this.minPriceFilter.value);
        const selectedAroma = this.aromaFilter.value;

        this.elements.forEach(element => {
            const price = parseFloat(element.getAttribute('data-price'));
            const aroma = element.getAttribute('data-aroma');

            const matchesPrice = (price >= minPrice) && (price <= maxPrice);
            const matchesAroma = selectedAroma === 'all' || aroma === selectedAroma;

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