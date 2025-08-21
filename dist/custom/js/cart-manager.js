// Enhanced cart functionality
document.querySelectorAll('.cart-button').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.getAttribute('data-product-id');
        const productName = this.getAttribute('data-product-name');
        const productPrice = parseFloat(this.getAttribute('data-product-price'));
        const quantityInput = this.parentElement.querySelector('.quantity-input');
        const quantity = parseInt(quantityInput.value) || 1;
        
        if (quantity > 0) {
            // Add to cart using your existing cart manager
            for (let i = 0; i < quantity; i++) {
                cartManager.addItem({
                    id: productId,
                    name: productName,
                    price: productPrice
                });
            }
            
            // Visual feedback
            this.style.backgroundColor = '#28a745';
            this.textContent = 'Aggiunto!';
            
            setTimeout(() => {
                this.style.backgroundColor = '';
                this.textContent = 'Aggiungi al carrello';
            }, 1500);
            
            // Reset quantity
            quantityInput.value = 0;
        } else {
            // Show error for invalid quantity
            quantityInput.style.borderColor = '#dc3545';
            quantityInput.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
            
            setTimeout(() => {
                quantityInput.style.borderColor = '';
                quantityInput.style.boxShadow = '';
            }, 2000);
        }
    });
});