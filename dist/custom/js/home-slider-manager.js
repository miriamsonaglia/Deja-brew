class ProductSlider {
    constructor(sliderId, itemsToShow = 3) {
        this.slider = document.getElementById(sliderId);
        if (!this.slider) return;
        
        this.sliderList = this.slider;
        this.items = this.sliderList.children;
        this.itemsToShow = itemsToShow;
        this.currentIndex = 0;
        this.itemWidth = 300; // 280px + 20px gap
        this.maxIndex = Math.max(0, this.items.length - this.itemsToShow);
        
        this.sliderId = sliderId.replace('-slider', '');
        this.backBtn = document.querySelector(`[data-slider="${this.sliderId}"].slider-backward`);
        this.forwardBtn = document.querySelector(`[data-slider="${this.sliderId}"].slider-forward`);
        this.indicatorsContainer = document.getElementById(`${this.sliderId}-indicators`);
        
        this.init();
    }

    init() {
        this.updateItemsToShow();
        this.createIndicators();
        this.updateSlider();
        this.bindEvents();
        
        window.addEventListener('resize', () => {
            this.updateItemsToShow();
            this.createIndicators();
            this.updateSlider();
        });
    }
    
    updateItemsToShow() {
        const containerWidth = this.slider.parentElement.offsetWidth;
        if (containerWidth < 500) {
            this.itemsToShow = 1;
        } else if (containerWidth < 800) {
            this.itemsToShow = 2;
        } else if (containerWidth < 1200) {
            this.itemsToShow = 3;
        } else {
            this.itemsToShow = 4;
        }
        this.maxIndex = Math.max(0, this.items.length - this.itemsToShow);
        this.currentIndex = Math.min(this.currentIndex, this.maxIndex);
    }
    
    createIndicators() {
        if (!this.indicatorsContainer || this.items.length <= this.itemsToShow) {
            if (this.indicatorsContainer) this.indicatorsContainer.style.display = 'none';
            return;
        }
        
        this.indicatorsContainer.style.display = 'flex';
        this.indicatorsContainer.innerHTML = '';
        const totalPages = Math.ceil(this.items.length / this.itemsToShow);
        
        for (let i = 0; i < totalPages; i++) {
            const indicator = document.createElement('span');
            indicator.className = 'slider-indicator';
            indicator.addEventListener('click', () => this.goToPage(i));
            this.indicatorsContainer.appendChild(indicator);
        }
        
        this.updateIndicators();
    }
    
    updateIndicators() {
        if (!this.indicatorsContainer) return;
        
        const indicators = this.indicatorsContainer.querySelectorAll('.slider-indicator');
        const currentPage = Math.floor(this.currentIndex / this.itemsToShow);
        
        indicators.forEach((indicator, index) => {
            indicator.classList.toggle('active', index === currentPage);
        });
    }
    
    bindEvents() {
        if (this.backBtn) {
            this.backBtn.addEventListener('click', () => this.prev());
        }
        if (this.forwardBtn) {
            this.forwardBtn.addEventListener('click', () => this.next());
        }
    }
    
    updateSlider() {
        const translateX = -this.currentIndex * this.itemWidth;
        this.sliderList.style.transform = `translateX(${translateX}px)`;
        
        // Update button states
        if (this.backBtn) {
            this.backBtn.disabled = this.currentIndex <= 0;
            this.backBtn.style.display = this.items.length <= this.itemsToShow ? 'none' : 'flex';
        }
        if (this.forwardBtn) {
            this.forwardBtn.disabled = this.currentIndex >= this.maxIndex;
            this.forwardBtn.style.display = this.items.length <= this.itemsToShow ? 'none' : 'flex';
        }
        
        this.updateIndicators();
    }
    
    next() {
        if (this.currentIndex < this.maxIndex) {
            this.currentIndex++;
            this.updateSlider();
        }
    }
    
    prev() {
        if (this.currentIndex > 0) {
            this.currentIndex--;
            this.updateSlider();
        }
    }
    
    goToPage(pageIndex) {
        this.currentIndex = Math.min(pageIndex * this.itemsToShow, this.maxIndex);
        this.updateSlider();
    }
}