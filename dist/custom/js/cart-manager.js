
async function updateCartCount() {
    try {
        const response = await fetch('./update-cart-count.php', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json'
            }
        });
        const data = await response.json();
        const cartBadge = document.querySelector('.cart-badge');
        if (cartBadge) {
            cartBadge.innerHTML = data.count;
        }
    } catch (error) {
        console.error('Error updating cart count:', error);
    }
}

async function updateCartQuantity(productId, quantity) {
    try {
            const response = await fetch('./append-product.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ 
                    productID: productId,
                    quantity: quantity,
                    type: 'carrello'
                })
            });

            if (!response.ok) {
                throw new Error('Network response was not ok');
            }

            await updateCartCount();
            const data = await response.json();
            console.log('Cart updated:', data);
        } catch (error) {
            console.error('Error updating cart quantity:', error);
        }
}

// Enhanced cart functionality that works on both home and product pages
document.addEventListener('DOMContentLoaded', function() {

    // Handle cart buttons
    document.querySelectorAll('.cart-button').forEach(button => {
        button.addEventListener('click', async function() {
            const productId = this.getAttribute('data-product-id');
            
            // Find quantity input - handle both structures
            let quantityInput = this.parentElement.querySelector('.quantity-input');
            if (!quantityInput) {
                // Fallback for product page structure
                quantityInput = document.querySelector(`input[data-product-id="${productId}"].quantity-input`);
            }
            
            const quantity = quantityInput ? parseInt(quantityInput.value) || 1 : 1;
            
            if (quantity > 0) {
                try {
                    const response = await fetch('./append-product.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ 
                            productID: productId, 
                            quantity: quantity, 
                            type: 'carrello'
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    // Visual feedback
                    const originalText = this.innerHTML;
                    const originalBg = this.style.backgroundColor;
                    
                    this.style.backgroundColor = '#28a745';
                    this.innerHTML = '<i class="bi bi-check"></i> Aggiunto!';
                    
                    // Update cart count
                    await updateCartCount();
                    
                    // Reset visual state
                    setTimeout(() => {
                        this.style.backgroundColor = originalBg;
                        this.innerHTML = originalText;
                    }, 1500);

                    // Reset quantity to 0 for home page, keep 1 for product page
                    if (quantityInput) {
                        quantityInput.value = 1;
                    }

                    const data = await response.json();
                    console.log('Cart updated:', data);

                } catch (error) {
                    console.error('Error adding to cart:', error);
                    
                    // Error visual feedback
                    const originalText = this.innerHTML;
                    this.style.backgroundColor = '#dc3545';
                    this.innerHTML = '<i class="bi bi-x"></i> Errore!';
                    
                    setTimeout(() => {
                        this.style.backgroundColor = '';
                        this.innerHTML = originalText;
                    }, 2000);
                }
            } else {
                // Show error for invalid quantity
                if (quantityInput) {
                    quantityInput.style.borderColor = '#dc3545';
                    quantityInput.style.boxShadow = '0 0 0 3px rgba(220, 53, 69, 0.1)';
                    
                    setTimeout(() => {
                        quantityInput.style.borderColor = '';
                        quantityInput.style.boxShadow = '';
                    }, 2000);
                }
            }
        });
    });

    document.querySelectorAll('.quantity-input').forEach(input => {
        input.addEventListener('change', async function() {
            let quantity = parseInt(this.value);
            let productId = this.getAttribute('data-product-id');
            if (!isNaN(quantity) && productId) {
                const response = await fetch('./append-product.php', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ 
                            productID: productId, 
                            quantity: quantity, 
                            type: 'carrello'
                        })
                    });

                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }

                    // Update cart count
                    await updateCartCount();

                    const data = await response.json();
                    console.log('Cart updated:', data);
            }
        });
    });
});