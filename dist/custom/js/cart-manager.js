class CartManager {
    constructor(badgeId) {
        this.count = 0;
        this.badge = document.getElementById(badgeId);
        this.items = []; // Array per memorizzare gli articoli del carrello
        
        if (!this.badge) {
            console.error(`Badge element with id "${badgeId}" not found`);
            return;
        }
        
        this.init();
    }
    
    init() {
        // Inizializza il badge
        this.updateBadge();
        
        // Aggiungi CSS per l'animazione bounce se non esiste già
        this.addBounceAnimation();
    }
    
    addBounceAnimation() {
        // Controlla se l'animazione bounce esiste già
        const style = document.createElement('style');
        style.textContent = `
            .cart-badge.bounce {
                animation: cartBounce 0.3s ease;
            }
            @keyframes cartBounce {
                0% { transform: scale(1); }
                50% { transform: scale(1.2); }
                100% { transform: scale(1); }
            }
        `;
        document.head.appendChild(style);
    }
    
    updateBadge() {
        if (!this.badge) return;
        
        this.badge.textContent = this.count;
        
        // Nascondi il badge se il carrello è vuoto
        if (this.count === 0) {
            this.badge.style.display = 'none';
        } else {
            this.badge.style.display = 'flex';
        }
        
        // Badge più grande per numeri a 2 cifre
        if (this.count > 9) {
            this.badge.classList.add('large');
        } else {
            this.badge.classList.remove('large');
        }
        
        // Animazione bounce
        this.badge.classList.add('bounce');
        setTimeout(() => this.badge.classList.remove('bounce'), 300);
    }
    
    addItem(product = null) {
        this.count++;
        
        // Se viene passato un prodotto, aggiungilo all'array
        if (product) {
            this.items.push(product);
        }
        
        this.updateBadge();
        this.onCartChange('add', product);
    }
    
    removeItem(productId = null) {
        if (this.count > 0) {
            this.count--;
            
            // Se viene passato un ID prodotto, rimuovilo dall'array
            if (productId && this.items.length > 0) {
                const index = this.items.findIndex(item => item.id === productId);
                if (index !== -1) {
                    this.items.splice(index, 1);
                } else {
                    // Se non trova l'ID, rimuovi l'ultimo elemento
                    this.items.pop();
                }
            } else {
                // Rimuovi l'ultimo elemento
                this.items.pop();
            }
            
            this.updateBadge();
            this.onCartChange('remove', productId);
        }
    }
    
    clearCart() {
        this.count = 0;
        this.items = [];
        this.updateBadge();
        this.onCartChange('clear');
    }
    
    getCount() {
        return this.count;
    }
    
    getItems() {
        return [...this.items]; // Restituisce una copia dell'array
    }
    
    setCount(newCount) {
        if (newCount >= 0) {
            this.count = newCount;
            this.updateBadge();
        }
    }
    
    // Callback per eventi del carrello
    onCartChange(action, data = null) {
        // Puoi personalizzare questo metodo per fare azioni quando il carrello cambia
        console.log(`Cart ${action}:`, { count: this.count, data });
        
        // Esempio: salva nel localStorage
        this.saveToStorage();
        
        // Emetti un evento personalizzato
        window.dispatchEvent(new CustomEvent('cartChanged', {
            detail: { action, count: this.count, items: this.items, data }
        }));
    }
    
    saveToStorage() {
        localStorage.setItem('cart', JSON.stringify({
            count: this.count,
            items: this.items
        }));
    }
    
    loadFromStorage() {
        try {
            const saved = localStorage.getItem('cart');
            if (saved) {
                const cartData = JSON.parse(saved);
                this.count = cartData.count || 0;
                this.items = cartData.items || [];
                this.updateBadge();
            }
        } catch (error) {
            console.error('Error loading cart from storage:', error);
        }
    }
}

// Uso della classe
const cart = new CartManager('cartBadge');

// Carica dati salvati (opzionale)
// cart.loadFromStorage();

// Esempi di utilizzo:

// Aggiungere elementi semplici
// cart.addItem();

// Aggiungere prodotti con dati
// cart.addItem({ id: 1, name: 'Caffè Espresso', price: 2.50 });

// Rimuovere elementi
// cart.removeItem();
// cart.removeItem(1); // Rimuovi prodotto con ID 1

// Altri metodi
// console.log(cart.getCount());
// console.log(cart.getItems());
// cart.clearCart();

// Ascoltare eventi del carrello
window.addEventListener('cartChanged', (event) => {
    console.log('Carrello cambiato:', event.detail);
    // Qui puoi aggiornare altre parti dell'UI
});

// Funzioni globali per compatibilità (se necessarie)
function addItem() { cart.addItem(); }
function removeItem() { cart.removeItem(); }
function clearCart() { cart.clearCart(); }