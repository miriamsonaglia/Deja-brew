async function updateCartCount() {
    fetch('./update-cart-count.php', {
        method: 'GET',
        headers: {
            'Content-Type': 'application/json'
        }
    }).then((res) => res.json()).then((data) => {
        document.querySelector('.cart-badge').innerHTML = data.count;
    });
}

// Enhanced cart functionality
document.querySelectorAll('.cart-button').forEach(button => {
    button.addEventListener('click', function() {
        const productId = this.getAttribute('data-product-id');
        const quantityInput = this.parentElement.querySelector('.quantity-input');
        const quantity = parseInt(quantityInput.value) || 1;
        if (quantity > 0) {
            fetch('./append-product.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ productID: productId, quantity: quantity, type: 'carrello'})
            }).then((response) => {
                if(!response.ok) {
                    throw new Error('Network response was not ok');
                } else {
                    // Visual feedback
                    this.style.backgroundColor = '#28a745';
                    this.textContent = 'Aggiunto!';
                    updateCartCount();
                    setTimeout(() => {
                        this.style.backgroundColor = '';
                        this.textContent = 'Aggiungi al carrello';
                    }, 1500);
            
                    // Reset quantity
                    quantityInput.value = 0;
                }
                return response.json();
            }).then((data) => {
                console.log(data);
            })
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