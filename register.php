<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione - Deja-brew</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #F2EFEA;
            font-family: 'Arial', sans-serif;
            color: #2C2C2C;
        }

        .card {
            background-color: #FAF8F6;
            border-radius: 1rem;
            border: none;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.12);
        }

        h2 {
            color: #5A2C2C;
        }

        a.text-primary {
            color: #CFA34A !important;
        }

        a.text-primary:hover {
            color: #5A2C2C !important;
        }

        .btn-info {
            background-color: #8C3B3B;
            border: none;
            color: #FFF;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .btn-info:hover {
            background-color: #5A2C2C;
        }

        .input-group .form-control {
            border-right: none;
        }

        .toggle-btn {
            background: #F8F8F8;
            border: 1px solid #ccc;
            border-left: none;
        }

        .toggle-btn:hover {
            background: #E7E3E0;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4" style="max-width: 500px; width: 100%;">

        <!-- Link indietro -->
        <div class="mb-3">
            <a href="login.php" class="text-decoration-underline d-inline-flex align-items-center text-primary fw-semibold">
                <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#8C3B3B" viewBox="0 0 24 24" class="me-1">
                    <path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                </svg>
                Hai già un account?
            </a>
        </div>

        <!-- Titolo -->
        <h2 class="text-center mb-4 fw-bold">Registrazione</h2>

        <!-- Form -->
        <form method="POST" action="register.php">
            <div class="row mb-3">
                <div class="col">
                    <label for="nome" class="form-label fw-semibold">Nome</label>
                    <input type="text" id="nome" name="nome" class="form-control" required>
                </div>
                <div class="col">
                    <label for="cognome" class="form-label fw-semibold">Cognome</label>
                    <input type="text" id="cognome" name="cognome" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="username" class="form-label fw-semibold">Username</label>
                <input type="text" id="username" name="username" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label fw-semibold">Email</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="row mb-3">
                <div class="col">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <div class="input-group">
                        <input type="password" id="password" name="password" class="form-control" required>
                        <button type="button" class="btn toggle-btn" id="togglePassword" aria-label="Mostra/Nascondi password">
                            <!-- Occhio barrato -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                                <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                                <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                                <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="col">
                    <label for="password_confirmation" class="form-label fw-semibold">Conferma Password</label>
                    <div class="input-group">
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                        <button type="button" class="btn toggle-btn" id="togglePasswordConfirm" aria-label="Mostra/Nascondi password">
                            <!-- Occhio barrato -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye-slash" viewBox="0 0 16 16">
                                <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                                <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                                <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Selezione ruolo -->
            <div class="text-center mb-4">
                <label class="form-label fw-semibold d-block mb-2">Seleziona il tuo ruolo</label>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role" id="role_acquirente" value="acquirente" checked>
                    <label class="form-check-label text-primary fw-semibold" for="role_acquirente">Acquirente</label>
                </div>
                <div class="form-check form-check-inline">
                    <input class="form-check-input" type="radio" name="role" id="role_venditore" value="venditore">
                    <label class="form-check-label text-primary fw-semibold" for="role_venditore">Venditore</label>
                </div>
            </div>

            <div class="d-grid">
                <button type="submit" class="btn btn-info fw-semibold">Registrati</button>
            </div>
        </form>
    </div>
</div>

<!-- JS Occhio -->
<script>
    function setupTogglePassword(buttonId, inputId) {
        const btn = document.getElementById(buttonId);
        const input = document.getElementById(inputId);
        const eyeOpen = `
            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-eye" viewBox="0 0 16 16">
                <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
                <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
            </svg>`;
        const eyeSlash = btn.innerHTML;

        btn.addEventListener('click', () => {
            const isPassword = input.type === 'password';
            input.type = isPassword ? 'text' : 'password';
            btn.innerHTML = isPassword ? eyeOpen : eyeSlash;
        });
    }

    setupTogglePassword('togglePassword', 'password');
    setupTogglePassword('togglePasswordConfirm', 'password_confirmation');
</script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>
