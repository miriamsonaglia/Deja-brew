// Quantity input validation
document.querySelectorAll('.quantity-input').forEach(input => {
    input.addEventListener('change', function() {
        const value = parseInt(this.value);
        if (value < 0) {
            this.value = 0;
        }
        if (value > 99) {
            this.value = 99;
        }
    });
    
    input.addEventListener('input', function() {
        // Remove any non-digit characters
        this.value = this.value.replace(/[^0-9]/g, '');
    });
});