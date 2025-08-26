class Filter {
    constructor() {
        this.elements = document.querySelectorAll('.slider-object');
    }

    addFilter(name, callback) {
        if (!this.filters[name]) {
            this.filters[name] = [];
        }
        this.filters[name].push(callback);
    }

    applyFilters(name, value) {
        if (!this.filters[name]) {
            return value;
        }
        return this.filters[name].reduce((acc, fn) => fn(acc), value);
    }
}