document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('live-search');
    const resultsList = document.getElementById('search-results');

    if (!searchInput || !resultsList) {
        console.error('Search input or results list not found in DOM.');
        return;
    }

    let debounceTimeout;
    let currentFocusIndex = -1;

    searchInput.addEventListener('input', function () {
        const query = searchInput.value.trim();

        clearTimeout(debounceTimeout);

        if (query.length < 2) {
            resultsList.style.display = 'none';
            resultsList.innerHTML = '';
            currentFocusIndex = -1;
            return;
        }

        debounceTimeout = setTimeout(() => {
            fetch(`./search-products.php?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    resultsList.innerHTML = '';
                    currentFocusIndex = -1;

                    if (data.length > 0) {
                        data.forEach((product, index) => {
                            const li = document.createElement('li');
                            li.className = 'list-group-item list-group-item-action d-flex align-items-center';
                            li.style.cursor = 'pointer';
                            li.setAttribute('data-index', index);
                            li.setAttribute('data-id', product.id);
                            if(!product.fotografia || product.fotografia.trim() === '') {
                                product.fotografia = './images/products/Standard_Blend.png';
                            }
                            li.innerHTML = `
                                <img src="${product.fotografia}" alt="${product.nome}" style="width: 40px; height: 40px; object-fit: cover; margin-right: 10px;">
                                <span>${product.nome}</span>
                            `;

                            li.addEventListener('click', () => {
                                window.location.href = `product.php?id=${product.id}`;
                            });

                            resultsList.appendChild(li);
                        });

                        resultsList.style.display = 'block';
                    } else {
                        resultsList.style.display = 'none';
                    }
                })
                .catch(error => console.error('Errore AJAX:', error));
        }, 300);
    });

    searchInput.addEventListener('keydown', function (e) {
        const items = resultsList.querySelectorAll('li');

        if (items.length === 0) return;

        if (e.key === 'ArrowDown') {
            e.preventDefault();
            currentFocusIndex = (currentFocusIndex + 1) % items.length;
            updateActiveItem(items);
        }

        if (e.key === 'ArrowUp') {
            e.preventDefault();
            currentFocusIndex = (currentFocusIndex - 1 + items.length) % items.length;
            updateActiveItem(items);
        }

        if (e.key === 'Enter' && currentFocusIndex >= 0) {
            e.preventDefault();
            const selectedItem = items[currentFocusIndex];
            if (selectedItem) {
                const productId = selectedItem.getAttribute('data-id');
                window.location.href = `product.php?id=${productId}`;
            }
        }
    });

    function updateActiveItem(items) {
        items.forEach(item => item.classList.remove('active'));
        if (currentFocusIndex >= 0 && currentFocusIndex < items.length) {
            items[currentFocusIndex].classList.add('active');
            // Scroll to the selected item if it's out of view
            items[currentFocusIndex].scrollIntoView({
                behavior: 'smooth',
                block: 'nearest'
            });
        }
    }

    document.addEventListener('click', function (e) {
        if (!searchInput.contains(e.target) && !resultsList.contains(e.target)) {
            resultsList.style.display = 'none';
            currentFocusIndex = -1;
        }
    });
});
