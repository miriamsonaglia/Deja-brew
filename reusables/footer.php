<!-- Footer -->
<footer class="footer-dejabrew bg-primary-brown text-white mt-auto sticky-bottom" role="contentinfo">
    <div class="container py-4">
        <div class="row align-items-center">
            <!-- Brand -->
            <div class="col-md-4 text-center text-md-start mb-3 mb-md-0">
                <h2 class="footer-brand h4 mb-0">Deja-brew</h2>
                <p class="footer-tagline mb-0">Il piacere del caffè artigianale</p>
            </div>

            <!-- Contatti -->
            <div class="col-md-4 text-center mb-3 mb-md-0">
                <nav aria-label="Contatti">
                    <p class="mb-2">
                        <i class="bi bi-telephone-fill me-2" aria-hidden="true"></i>
                        <a href="tel:+390212345678" class="footer-link">+39 02 1234 5678</a>
                    </p>
                    <p class="mb-0">
                        <i class="bi bi-envelope-fill me-2" aria-hidden="true"></i>
                        <a href="mailto:support@dejabrew.it" class="footer-link">support@dejabrew.it</a>
                    </p>
                </nav>
            </div>

            <!-- Social & Copyright -->
            <div class="col-md-4 text-center text-md-end">
                <div class="social-links mb-2">
                    <a href="#" class="social-link me-2" aria-label="Seguici su Facebook">
                        <i class="bi bi-facebook" aria-hidden="true"></i>
                    </a>
                    <a href="#" class="social-link me-2" aria-label="Seguici su Instagram">
                        <i class="bi bi-instagram" aria-hidden="true"></i>
                    </a>
                    <a href="#" class="social-link" aria-label="Seguici su Twitter">
                        <i class="bi bi-twitter" aria-hidden="true"></i>
                    </a>
                </div>
                <p class="footer-copyright mb-0">
                    &copy; <?php echo date('Y'); ?> Deja-brew
                </p>
            </div>
        </div>
    </div>
</footer>

<style>
/* Footer Styles */
.footer-dejabrew {
    background-color: var(--primary-brown);
    border-top: 3px solid var(--secondary-red);
    margin-top: auto;
}

.footer-brand {
    font-weight: bold;
    color: #fff;
    letter-spacing: 0.5px;
}

.footer-tagline {
    color: rgba(255, 255, 255, 0.8);
    font-size: 0.9rem;
    font-style: italic;
}

.footer-link {
    color: rgba(255, 255, 255, 0.85);
    text-decoration: none;
    transition: all 0.3s ease;
}

.footer-link:hover,
.footer-link:focus {
    color: #fff;
    text-decoration: underline;
}

.footer-link:focus-visible {
    outline: 2px solid #fff;
    outline-offset: 2px;
    border-radius: 2px;
}

.social-links {
    display: flex;
    align-items: center;
    justify-content: center;
}

.social-links .social-link {
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background-color: rgba(255, 255, 255, 0.1);
    color: #fff;
    border-radius: 50%;
    text-decoration: none;
    transition: all 0.3s ease;
    font-size: 1.1rem;
}

.social-links .social-link:hover,
.social-links .social-link:focus {
    background-color: var(--secondary-red);
    color: #fff;
    transform: translateY(-2px);
}

.social-links .social-link:focus-visible {
    outline: 2px solid #fff;
    outline-offset: 2px;
}

.footer-copyright {
    color: rgba(255, 255, 255, 0.7);
    font-size: 0.85rem;
}

/* Responsive */
@media (max-width: 768px) {
    .footer-dejabrew .container {
        text-align: center;
    }

    .social-links {
        justify-content: center;
    }
}

@media (min-width: 769px) {
    .social-links {
        justify-content: flex-end;
    }
}
</style>
