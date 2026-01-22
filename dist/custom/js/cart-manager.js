
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
        } catch (error) {
            console.error('Error updating cart quantity:', error);
        }
}

async function removeFromCart(productId) {
    try {
        const response = await fetch('./remove-product.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                productID: productId,
                type: 'carrello'
            })
        });

        if (!response.ok) {
            throw new Error('Network response was not ok');
        }

        await updateCartCount();

        const data = await response.json();

        // Rimuovi elemento DOM
        const productRow = document.querySelector(`.cart-item[data-product-id="${productId}"]`);
        if (productRow) {
            productRow.remove();
        }

        // Ricalcola i totali
        recalculateCartTotals();

        // Se il carrello è vuoto, mostra stato "vuoto"
        const remainingItems = document.querySelectorAll('.cart-item');
        if (remainingItems.length === 0) {
            document.querySelector('.cart-content').remove();
            document.querySelector('#main-container').innerHTML += `
                <div class="empty-state">
                <i class="bi bi-cart-x display-1 text-muted mb-4"></i>
                <h3 class="text-primary-brown mb-3">Il tuo carrello è vuoto</h3>
                <p class="text-muted mb-4">Aggiungi alcuni prodotti per iniziare i tuoi acquisti!</p>
                <a href="home.php" class="btn btn-primary-custom">
                    <i class="bi bi-shop me-2"></i>
                    Inizia lo Shopping
                </a>
            </div>
            `;
        }

    } catch (error) {
        console.error('Error removing product from cart:', error);
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
});