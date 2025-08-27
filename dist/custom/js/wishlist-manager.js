// Unified wishlist functionality for both home and product pages
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.wish-button').forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.getAttribute('data-product-id');
            const icon = this.querySelector('i');
            const isWished = icon.classList.contains('bi-heart-fill');
            
            try {
                let response;
                
                if (isWished) {
                    // Remove from wishlist
                    response = await fetch('./remove-product.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ 
                            productID: productId, 
                            type: 'desideri'
                        })
                    });
                } else {
                    // Add to wishlist
                    response = await fetch('./append-product.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ 
                            productID: productId,  
                            quantity: 1, 
                            type: 'desideri'
                        })
                    });
                }

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                // Update UI based on action
                if (isWished) {
                    // Removed from wishlist
                    icon.classList.remove('bi-heart-fill');
                    icon.classList.add('bi-heart');
                    this.style.backgroundColor = '';
                    this.style.borderColor = '#ff4444';
                    this.style.color = '#ff4444';
                    this.title = 'Aggiungi alla wishlist';
                } else {
                    // Added to wishlist
                    icon.classList.remove('bi-heart');
                    icon.classList.add('bi-heart-fill');
                    this.style.backgroundColor = 'rgba(255, 68, 68, 0.1)';
                    this.style.borderColor = '#ff4444';
                    this.style.color = '#ff4444';
                    this.title = 'Rimuovi dalla wishlist';
                }

                const data = await response.json();
                console.log('Wishlist updated:', data);

                // Optional: Show success message
                this.style.transform = 'scale(0.95)';
                setTimeout(() => {
                    this.style.transform = 'scale(1)';
                }, 150);

            } catch (error) {
                console.error('Error updating wishlist:', error);
                
                // Show error feedback
                const originalColor = this.style.backgroundColor;
                this.style.backgroundColor = 'rgba(220, 53, 69, 0.1)';
                
                setTimeout(() => {
                    this.style.backgroundColor = originalColor;
                }, 1000);
            }
        });
    });
});