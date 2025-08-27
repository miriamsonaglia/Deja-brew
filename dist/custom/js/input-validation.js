// Enhanced quantity input validation for both home and product pages
document.addEventListener('DOMContentLoaded', function() {
    // Handle all quantity inputs
    document.querySelectorAll('.quantity-input').forEach(input => {
        // Input validation on change
        input.addEventListener('change', function() {
            let value = parseInt(this.value);
            
            // Validate range
            if (isNaN(value) || value < 0) {
                this.value = 0;
                value = 0;
            }
            if (value > 99) {
                this.value = 99;
                value = 99;
            }
            
            // Update buy now quantity if on product page
            const buyNowInput = document.getElementById('buyNowQuantity');
            if (buyNowInput && this.dataset.productId) {
                buyNowInput.value = Math.max(1, value); // Buy now should be at least 1
            }
            
            // Visual feedback for valid input
            this.style.borderColor = value >= 0 && value <= 99 ? '#28a745' : '#dc3545';
            setTimeout(() => {
                this.style.borderColor = '';
            }, 1000);
        });
        
        // Real-time input filtering
        input.addEventListener('input', function() {
            // Remove any non-digit characters
            let value = this.value.replace(/[^0-9]/g, '');
            
            // Limit length to prevent very large numbers
            if (value.length > 2) {
                value = value.substring(0, 2);
            }
            
            this.value = value;
        });
        
        // Handle keyboard events
        input.addEventListener('keydown', function(e) {
            // Allow: backspace, delete, tab, escape, enter
            if ([8, 9, 27, 13, 46].includes(e.keyCode) ||
                // Allow: Ctrl+A, Ctrl+C, Ctrl+V, Ctrl+X
                (e.keyCode === 65 && e.ctrlKey === true) ||
                (e.keyCode === 67 && e.ctrlKey === true) ||
                (e.keyCode === 86 && e.ctrlKey === true) ||
                (e.keyCode === 88 && e.ctrlKey === true) ||
                // Allow: home, end, left, right, down, up
                (e.keyCode >= 35 && e.keyCode <= 40)) {
                return;
            }
            
            // Ensure that it is a number and stop the keypress
            if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
                e.preventDefault();
            }
        });
        
        // Handle focus events for better UX
        input.addEventListener('focus', function() {
            this.select(); // Select all text on focus
        });
        
        // Handle blur to ensure valid value
        input.addEventListener('blur', function() {
            if (this.value === '' || parseInt(this.value) < 0) {
                this.value = 0;
            }
        });
    });
    
    // Add increment/decrement functionality for product page if buttons exist
    const incrementBtn = document.getElementById('btnIncrement');
    const decrementBtn = document.getElementById('btnDecrement');
    const quantityDisplay = document.getElementById('quantita');
    
    if (incrementBtn && decrementBtn && quantityDisplay) {
        incrementBtn.addEventListener('click', () => {
            let val = parseInt(quantityDisplay.value);
            if (val < 99) {
                quantityDisplay.value = ++val;
                // Update all related inputs
                document.querySelectorAll('input[name="quantita"]').forEach(input => {
                    input.value = val;
                });
            }
        });

        decrementBtn.addEventListener('click', () => {
            let val = parseInt(quantityDisplay.value);
            if (val > 1) {
                quantityDisplay.value = --val;
                // Update all related inputs
                document.querySelectorAll('input[name="quantita"]').forEach(input => {
                    input.value = val;
                });
            }
        });
    }
});