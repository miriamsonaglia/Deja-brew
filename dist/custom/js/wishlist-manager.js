// Wishlist functionality
document.querySelectorAll('.wish-button').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.getAttribute('data-product-id');
        const icon = this.querySelector('i');
        const isWished = icon.classList.contains('bi-heart-fill');
        
        // CONVERT FAKE SCRIPT TO EFFECTIVE AJAX CALL
        if (isWished) {
            // Remove from wishlist
            fetch('./remove-product.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ productID: productId, type: 'desideri'})
            }).then((response) => {
                if(!response.ok) {
                    throw new Error('Network response was not ok');
                } else {
                    icon.classList.remove('bi-heart-fill');
                    icon.classList.add('bi-heart');
                    this.style.backgroundColor = '';
                    this.style.borderColor = '#ff4444';
                    this.style.color = '#ff4444';
                    this.title = 'Aggiungi alla wishlist';
                }
                return response.json();
            }).then((data) => {
                console.log(data);
            })
        } else {
            // Add to wishlist
            fetch('./append-product.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ productID: productId,  quantity: 1, type: 'desideri'})
            }).then((response) => {
                if(!response.ok) {
                    throw new Error('Network response was not ok');
                } else {
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill');
                    this.style.backgroundColor = 'rgba(255, 68, 68, 0.1)';
                    this.style.borderColor = '#ff4444';
                    this.style.color = '#ff4444';
                    this.title = 'Rimuovi dalla wishlist';
                }
                return response.json();
            }).then((data) => {
                console.log(data);
            })
        }
    });
});
