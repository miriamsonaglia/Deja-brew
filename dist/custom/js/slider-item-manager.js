document.querySelectorAll('.slider-object').forEach(item => {
    item.addEventListener('click', function() {
        const productId = this.getAttribute('data-product-id');
        // Redirect to product detail page
        window.location.href = `product.php?id=${productId}`;
    });
});