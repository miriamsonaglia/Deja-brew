// Wishlist functionality
document.querySelectorAll('.wish-button').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.getAttribute('data-product-id');
        const icon = this.querySelector('i');
        const isWished = icon.classList.contains('bi-heart-fill');
        
        if (isWished) {
            // Remove from wishlist
            icon.classList.remove('bi-heart-fill');
            icon.classList.add('bi-heart');
            this.style.backgroundColor = '';
            this.style.borderColor = '#ff4444';
            this.style.color = '#ff4444';
            this.title = 'Aggiungi alla wishlist';
        } else {
            // Add to wishlist
            icon.classList.remove('bi-heart');
            icon.classList.add('bi-heart-fill');
            this.style.backgroundColor = 'rgba(255, 68, 68, 0.1)';
            this.style.borderColor = '#ff4444';
            this.style.color = '#ff4444';
            this.title = 'Rimuovi dalla wishlist';
            
            // Here you would typically make an AJAX call to save the wishlist item
            console.log(`Added product ${productId} to wishlist`);
        }
    });
});
