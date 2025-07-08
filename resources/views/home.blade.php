<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $siteName ?? 'Il Mio Ecommerce' }} - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .product-slider {
            overflow-x: auto;
            overflow-y: hidden;
            scroll-behavior: smooth;
            -webkit-overflow-scrolling: touch;
        }
        .product-slider::-webkit-scrollbar {
            height: 8px;
        }
        .product-slider::-webkit-scrollbar-track {
            background: #f1f1f1;
            border-radius: 4px;
        }
        .product-slider::-webkit-scrollbar-thumb {
            background: #888;
            border-radius: 4px;
        }
        .product-slider::-webkit-scrollbar-thumb:hover {
            background: #555;
        }
        .product-card {
            min-width: 280px;
            max-width: 280px;
            flex-shrink: 0;
            transition: transform 0.3s ease, box-shadow 0.3s ease;
        }
        .product-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        }
        .product-image {
            height: 200px;
            object-fit: cover;
            transition: transform 0.3s ease;
        }
        .product-card:hover .product-image {
            transform: scale(1.05);
        }
        .navbar-brand {
            font-weight: 700;
            font-size: 1.5rem;
        }
        .hero-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 15px;
        }
        .category-header {
            border-bottom: 3px solid #007bff;
            padding-bottom: 10px;
            margin-bottom: 30px;
        }
        .btn-scroll {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            z-index: 10;
            background: rgba(255, 255, 255, 0.9);
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 50%;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            transition: all 0.3s ease;
        }
        .btn-scroll:hover {
            background: white;
            transform: translateY(-50%) scale(1.1);
        }
        .btn-scroll.btn-prev {
            left: 10px;
        }
        .btn-scroll.btn-next {
            right: 10px;
        }
        .price-original {
            text-decoration: line-through;
            color: #6c757d;
            font-size: 0.9rem;
        }
        .price-current {
            color: #dc3545;
            font-weight: bold;
            font-size: 1.2rem;
        }
        .discount-badge {
            position: absolute;
            top: 10px;
            left: 10px;
            background: #dc3545;
            color: white;
            padding: 5px 10px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: bold;
        }
        .rating-stars {
            color: #ffc107;
            font-size: 0.9rem;
        }
        .category-section {
            position: relative;
            margin-bottom: 50px;
        }
        .search-container {
            position: relative;
            max-width: 500px;
        }
        .search-container .btn {
            position: absolute;
            right: 5px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background: transparent;
            color: #6c757d;
        }
        .search-container .btn:hover {
            color: #495057;
        }
        @media (max-width: 768px) {
            .product-card {
                min-width: 250px;
                max-width: 250px;
            }
            .hero-section h2 {
                font-size: 1.8rem;
            }
            .hero-section p {
                font-size: 1rem;
            }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm sticky-top">
        <div class="container">
            <!-- Hamburger Button -->
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <!-- Brand/Logo -->
            <a class="navbar-brand mx-auto mx-lg-0" href="{{ route('home') }}">
                {{ $siteName ?? 'Il Mio Ecommerce' }}
            </a>

            <!-- Search Bar (Desktop) -->
            <div class="d-none d-lg-flex flex-grow-1 justify-content-center">
                <div class="search-container w-100">
                    <form action="{{ route('search') }}" method="GET" class="d-flex">
                        <input 
                            class="form-control form-control-lg" 
                            type="search" 
                            name="q" 
                            placeholder="Cerca prodotti..."
                            value="{{ request('q') }}"
                        >
                        <button class="btn" type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </form>
                </div>
            </div>

            <!-- User Actions -->
            <div class="d-flex align-items-center">
                <a href="{{ route('account') }}" class="btn btn-outline-primary me-2 d-none d-lg-block">
                    <i class="fas fa-user me-1"></i>Account
                </a>
                <a href="{{ route('cart') }}" class="btn btn-primary position-relative">
                    <i class="fas fa-shopping-cart"></i>
                    @if(isset($cartCount) && $cartCount > 0)
                        <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                            {{ $cartCount }}
                        </span>
                    @endif
                </a>
            </div>

            <!-- Mobile Menu -->
            <div class="collapse navbar-collapse" id="navbarNav">
                <div class="navbar-nav ms-auto mt-3 mt-lg-0">
                    <!-- Search Bar (Mobile) -->
                    <div class="d-lg-none mb-3">
                        <form action="{{ route('search') }}" method="GET">
                            <div class="search-container">
                                <input 
                                    class="form-control" 
                                    type="search" 
                                    name="q" 
                                    placeholder="Cerca prodotti..."
                                    value="{{ request('q') }}"
                                >
                                <button class="btn" type="submit">
                                    <i class="fas fa-search"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                    
                    <!-- Mobile Menu Items -->
                    <a class="nav-link d-lg-none" href="{{ route('account') }}">
                        <i class="fas fa-user me-2"></i>Account
                    </a>
                    <a class="nav-link d-lg-none" href="{{ route('categories') }}">
                        <i class="fas fa-th-large me-2"></i>Categorie
                    </a>
                    <a class="nav-link d-lg-none" href="{{ route('offers') }}">
                        <i class="fas fa-percent me-2"></i>Offerte
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="container my-5">
        <!-- Hero Section -->
        <section class="hero-section text-white p-5 mb-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h2 class="display-5 fw-bold mb-3">Benvenuto nel nostro store!</h2>
                    <p class="lead mb-4">Scopri i migliori prodotti alle migliori offerte. Qualità garantita e spedizione veloce.</p>
                    <a href="{{ route('categories') }}" class="btn btn-light btn-lg">
                        <i class="fas fa-rocket me-2"></i>Esplora Ora
                    </a>
                </div>
                <div class="col-lg-4 text-center">
                    <i class="fas fa-shopping-bag fa-5x opacity-50"></i>
                </div>
            </div>
        </section>

        <!-- Categories with Products -->
        @forelse($categories as $categoria)
            <section class="category-section">
                <!-- Category Header -->
                <div class="d-flex justify-content-between align-items-center category-header">
                    <h2 class="h3 mb-0">
                        <i class="fas fa-coffee me-2 text-primary"></i>
                        {{ $categoria->descrizione }}
                    </h2>
                    <a href="{{ route('categoria.show', $categoria->id) }}" class="btn btn-outline-primary">
                        Vedi tutti
                        <i class="fas fa-arrow-right ms-2"></i>
                    </a>
                </div>

                <!-- Products Slider -->
                <div class="position-relative">
                    <div class="product-slider d-flex gap-3 pb-3" id="slider-{{ $categoria->id }}">
                        @forelse($categoria->prodotti()->limit(8)->get() as $prodotto)
                            <div class="product-card">
                                <div class="card h-100 border-0 shadow-sm">
                                    <!-- Product Image -->
                                    <div class="position-relative overflow-hidden">
                                        <img 
                                            src="{{ $prodotto->fotografia ? asset('storage/' . $prodotto->fotografia) : asset('images/placeholder-product.jpg') }}" 
                                            class="card-img-top product-image"
                                            alt="{{ $prodotto->nome }}"
                                            loading="lazy"
                                        >
                                        
                                        <!-- Intensity Badge -->
                                        @if($prodotto->intensita)
                                            <div class="discount-badge bg-warning text-dark">
                                                Intensità {{ $prodotto->intensita }}
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Product Info -->
                                    <div class="card-body d-flex flex-column">
                                        <h5 class="card-title">{{ Str::limit($prodotto->nome, 50) }}</h5>
                                        
                                        <!-- Product Details -->
                                        <div class="text-muted small mb-2">
                                            @if($prodotto->tipo)
                                                <div><strong>Tipo:</strong> {{ $prodotto->tipo }}</div>
                                            @endif
                                            @if($prodotto->provenienza)
                                                <div><strong>Provenienza:</strong> {{ $prodotto->provenienza }}</div>
                                            @endif
                                            @if($prodotto->peso)
                                                <div><strong>Peso:</strong> {{ $prodotto->peso }}g</div>
                                            @endif
                                            @if($prodotto->aroma)
                                                <div><strong>Aroma:</strong> {{ $prodotto->aroma->nome ?? 'N/A' }}</div>
                                            @endif
                                        </div>

                                        <!-- Rating -->
                                        @php
                                            $averageRating = $prodotto->recensioni()->avg('voto') ?? 0;
                                            $reviewsCount = $prodotto->recensioni()->count();
                                        @endphp
                                        @if($averageRating > 0)
                                            <div class="rating-stars mb-2">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <i class="fas fa-star {{ $i <= $averageRating ? '' : 'text-muted' }}"></i>
                                                @endfor
                                                <span class="text-muted small ms-1">({{ $reviewsCount }})</span>
                                            </div>
                                        @endif

                                        <!-- Price and Vendor -->
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div>
                                                <div class="price-current">€{{ number_format($prodotto->prezzo, 2) }}</div>
                                                @if($prodotto->venditore)
                                                    <small class="text-muted">
                                                        <i class="fas fa-store me-1"></i>{{ $prodotto->venditore->nome_negozio ?? 'Venditore' }}
                                                    </small>
                                                @endif
                                            </div>
                                            
                                            <!-- Always Available (no stock field) -->
                                            <small class="text-success">
                                                <i class="fas fa-check-circle me-1"></i>Disponibile
                                            </small>
                                        </div>

                                        <!-- Add to Cart Button -->
                                        <div class="mt-auto">
                                            <form action="{{ route('cart.add') }}" method="POST">
                                                @csrf
                                                <input type="hidden" name="prodotto_id" value="{{ $prodotto->id }}">
                                                <button type="submit" class="btn btn-primary w-100">
                                                    <i class="fas fa-shopping-cart me-2"></i>Aggiungi al Carrello
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="col-12">
                                <div class="alert alert-info text-center">
                                    <i class="fas fa-info-circle me-2"></i>
                                    Nessun prodotto disponibile in questa categoria al momento.
                                </div>
                            </div>
                        @endforelse
                    </div>

                    <!-- Scroll Buttons -->
                    @if($categoria->prodotti()->count() > 4)
                        <button class="btn btn-scroll btn-prev" onclick="scrollSlider('slider-{{ $categoria->id }}', -300)">
                            <i class="fas fa-chevron-left"></i>
                        </button>
                        <button class="btn btn-scroll btn-next" onclick="scrollSlider('slider-{{ $categoria->id }}', 300)">
                            <i class="fas fa-chevron-right"></i>
                        </button>
                    @endif
                </div>
            </section>
        @empty
            <!-- No Categories Available -->
            <div class="text-center py-5">
                <i class="fas fa-box-open fa-5x text-muted mb-3"></i>
                <h3 class="text-muted">Nessuna categoria disponibile</h3>
                <p class="text-muted">Stiamo aggiornando il nostro catalogo. Torna presto!</p>
            </div>
        @endforelse

        <!-- Newsletter Section -->
        <section class="bg-light rounded-3 p-4 p-lg-5 my-5">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h3 class="h4 mb-2">Rimani aggiornato!</h3>
                    <p class="text-muted mb-3 mb-lg-0">Iscriviti alla nostra newsletter per ricevere offerte esclusive e novità.</p>
                </div>
                <div class="col-lg-4">
                    <form action="{{ route('newsletter.subscribe') }}" method="POST" class="d-flex">
                        @csrf
                        <input type="email" name="email" class="form-control me-2" placeholder="La tua email" required>
                        <button type="submit" class="btn btn-primary">Iscriviti</button>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <!-- Footer -->
    <footer class="bg-dark text-light py-5">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 mb-4">
                    <h5 class="fw-bold mb-3">{{ $siteName ?? 'Il Mio Ecommerce' }}</h5>
                    <p class="text-muted">Il tuo negozio online di fiducia per prodotti di qualità. Spedizione veloce e assistenza clienti sempre disponibile.</p>
                    <div class="d-flex gap-3">
                        <a href="#" class="text-light"><i class="fab fa-facebook fa-lg"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-instagram fa-lg"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-twitter fa-lg"></i></a>
                        <a href="#" class="text-light"><i class="fab fa-youtube fa-lg"></i></a>
                    </div>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Navigazione</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('home') }}" class="text-muted text-decoration-none">Home</a></li>
                        <li><a href="{{ route('categories') }}" class="text-muted text-decoration-none">Categorie</a></li>
                        <li><a href="{{ route('offers') }}" class="text-muted text-decoration-none">Offerte</a></li>
                        <li><a href="{{ route('contact') }}" class="text-muted text-decoration-none">Contatti</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Servizio Clienti</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('about') }}" class="text-muted text-decoration-none">Chi Siamo</a></li>
                        <li><a href="{{ route('shipping') }}" class="text-muted text-decoration-none">Spedizioni</a></li>
                        <li><a href="{{ route('returns') }}" class="text-muted text-decoration-none">Resi</a></li>
                        <li><a href="{{ route('faq') }}" class="text-muted text-decoration-none">FAQ</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Account</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('account') }}" class="text-muted text-decoration-none">Il Mio Account</a></li>
                        <li><a href="{{ route('orders') }}" class="text-muted text-decoration-none">I Miei Ordini</a></li>
                        <li><a href="{{ route('wishlist') }}" class="text-muted text-decoration-none">Lista Desideri</a></li>
                        <li><a href="{{ route('cart') }}" class="text-muted text-decoration-none">Carrello</a></li>
                    </ul>
                </div>
                <div class="col-lg-2 col-md-6 mb-4">
                    <h6 class="fw-bold mb-3">Legale</h6>
                    <ul class="list-unstyled">
                        <li><a href="{{ route('privacy') }}" class="text-muted text-decoration-none">Privacy Policy</a></li>
                        <li><a href="{{ route('terms') }}" class="text-muted text-decoration-none">Termini di Servizio</a></li>
                        <li><a href="{{ route('cookies') }}" class="text-muted text-decoration-none">Cookie Policy</a></li>
                    </ul>
                </div>
            </div>
            <hr class="my-4">
            <div class="text-center">
                <p class="mb-0 text-muted">&copy; {{ date('Y') }} {{ $siteName ?? 'Il Mio Ecommerce' }}. Tutti i diritti riservati.</p>
            </div>
        </div>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <!-- Custom JavaScript -->
    <script>
        // Scroll slider function
        function scrollSlider(sliderId, scrollAmount) {
            const slider = document.getElementById(sliderId);
            slider.scrollBy({
                left: scrollAmount,
                behavior: 'smooth'
            });
        }

        // Auto-hide scroll buttons based on scroll position
        document.addEventListener('DOMContentLoaded', function() {
            const sliders = document.querySelectorAll('.product-slider');
            
            sliders.forEach(slider => {
                const categorySection = slider.closest('.category-section');
                const prevBtn = categorySection.querySelector('.btn-prev');
                const nextBtn = categorySection.querySelector('.btn-next');
                
                if (!prevBtn || !nextBtn) return;
                
                function updateScrollButtons() {
                    const isAtStart = slider.scrollLeft === 0;
                    const isAtEnd = slider.scrollLeft >= slider.scrollWidth - slider.clientWidth;
                    
                    prevBtn.style.display = isAtStart ? 'none' : 'block';
                    nextBtn.style.display = isAtEnd ? 'none' : 'block';
                }
                
                // Initial check
                updateScrollButtons();
                
                // Update on scroll
                slider.addEventListener('scroll', updateScrollButtons);
                
                // Update on resize
                window.addEventListener('resize', updateScrollButtons);
            });
        });

        // Add to cart with loading state
        document.addEventListener('DOMContentLoaded', function() {
            const cartForms = document.querySelectorAll('form[action*="cart.add"]');
            
            cartForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const button = this.querySelector('button[type="submit"]');
                    const originalText = button.innerHTML;
                    
                    button.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Aggiungendo...';
                    button.disabled = true;
                    
                    // Re-enable after 2 seconds (or handle with actual response)
                    setTimeout(() => {
                        button.innerHTML = originalText;
                        button.disabled = false;
                    }, 2000);
                });
            });
        });

        // Search functionality
        document.addEventListener('DOMContentLoaded', function() {
            const searchForms = document.querySelectorAll('form[action*="search"]');
            
            searchForms.forEach(form => {
                form.addEventListener('submit', function(e) {
                    const input = this.querySelector('input[name="q"]');
                    const searchTerm = input.value.trim();
                    
                    if (!searchTerm) {
                        e.preventDefault();
                        input.focus();
                        return false;
                    }
                });
            });
        });
    </script>
</body>
</html>