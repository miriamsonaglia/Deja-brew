<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Login - Deja-brew</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap 5 -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background-color: #F2EFEA;
            color: #2C2C2C;
            font-family: 'Arial', sans-serif;
        }

        .card {
            background-color: #FFFFFF;
            border-radius: 1rem;
            border: none;
            box-shadow: 0 0 12px rgba(0, 0, 0, 0.12);
        }

        h1 {
            color: #5A2C2C;
        }

        .btn-info {
            background-color: #8C3B3B;
            border: none;
            color: #FFFFFF;
            font-weight: 600;
            transition: background-color 0.3s;
        }

        .btn-info:hover {
            background-color: #5A2C2C;
        }

        .btn-link {
            color: #8C3B3B;
            font-weight: 500;
        }

        .btn-link:hover {
            color: #5A2C2C;
        }

        .toggle-password-btn {
            background: #F8F8F8;
            border-left: none;
            border: 1px solid #ccc;
        }

        .toggle-password-btn:hover {
            background: #E7E3E0;
        }

        .input-group .form-control {
            border-right: none;
        }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center align-items-center min-vh-100">
    <div class="card shadow p-4" style="max-width: 450px; width: 100%; border-radius: 1rem; background-color: #FAF8F6;">

        <h1 class="text-center mb-4 fw-bold">Deja-brew</h1>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($_SESSION['error']) ?></div>
        <?php endif; ?>

        <form method="POST" action="authentication.php?action=login">
            <input type="hidden" name="_token" value="<?= $_SESSION['_token'] ?? '' ?>">

            <div class="mb-3">
                <label class="form-label fw-semibold">Email</label>
                <input type="email" class="form-control email-input" name="email" required>
            </div>

            <div class="mb-3">
                <label class="form-label fw-semibold">Password</label>
                <div class="input-group">
                    <input type="password" class="form-control password-input" name="password" required>
                    <button type="button" class="btn toggle-password-btn" aria-label="Mostra/Nascondi password">
                        <span class="toggle-icon">
                            <!-- icona occhio barrato iniziale -->
                            <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                 class="bi bi-eye-slash" viewBox="0 0 16 16">
                                <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
                                <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
                                <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
                            </svg>
                        </span>
                    </button>
                </div>
            </div>

            <input type="hidden" class="user-role" name="role" value="acquirente">

            <div class="d-grid mb-2">
                <button type="submit" class="btn btn-info fw-semibold">
                    Accedi come <span class="role-label">acquirente</span>
                </button>
            </div>

            <div class="text-center">
                <button type="button" class="btn btn-link p-0 text-decoration-underline change-role">
                    Accesso per venditori
                </button>
            </div>
        </form>

        <div class="text-center mt-3 small fw-bold">
            Non sei ancora uno di noi?<br>
            <a href="register.php" class="text-decoration-underline text-primary fw-semibold">Registrati</a><br>
        </div>

        <div class="text-center mt-1 small">
            <span>oppure</span><br>
            <a href="guest.php" class="text-decoration-underline text-primary fw-semibold">Prosegui senza registrarti</a>
        </div>
    </div>
</div>

<script>
    let currentRole = 'acquirente';

    function toggleRole() {
        currentRole = currentRole === 'acquirente' ? 'venditore' : 'acquirente';
        document.querySelector('.user-role').value = currentRole;
        document.querySelector('.role-label').innerText = currentRole;
        document.querySelector('.change-role').innerText = `Cambia ruolo in ${currentRole === 'acquirente' ? 'venditore' : 'acquirente'}`;
    }

    document.querySelector('.change-role').addEventListener('click', toggleRole);

    const togglePasswordBtn = document.querySelector('.toggle-password-btn');
    const toggleIcon = document.querySelector('.toggle-icon');
    const passwordInput = document.querySelector('.password-input');

    const eyeOpen = `
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
             class="bi bi-eye" viewBox="0 0 16 16">
            <path d="M16 8s-3-5.5-8-5.5S0 8 0 8s3 5.5 8 5.5S16 8 16 8M1.173 8a13 13 0 0 1 1.66-2.043C4.12 4.668 5.88 3.5 8 3.5s3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755C11.879 11.332 10.119 12.5 8 12.5s-3.879-1.168-5.168-2.457A13 13 0 0 1 1.172 8z"/>
            <path d="M8 5.5a2.5 2.5 0 1 0 0 5 2.5 2.5 0 0 0 0-5M4.5 8a3.5 3.5 0 1 1 7 0 3.5 3.5 0 0 1-7 0"/>
        </svg>`;

    const eyeSlash = `
        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
             class="bi bi-eye-slash" viewBox="0 0 16 16">
            <path d="M13.359 11.238C15.06 9.72 16 8 16 8s-3-5.5-8-5.5a7 7 0 0 0-2.79.588l.77.771A6 6 0 0 1 8 3.5c2.12 0 3.879 1.168 5.168 2.457A13 13 0 0 1 14.828 8q-.086.13-.195.288c-.335.48-.83 1.12-1.465 1.755q-.247.248-.517.486z"/>
            <path d="M11.297 9.176a3.5 3.5 0 0 0-4.474-4.474l.823.823a2.5 2.5 0 0 1 2.829 2.829zm-2.943 1.299.822.822a3.5 3.5 0 0 1-4.474-4.474l.823.823a2.5 2.5 0 0 0 2.829 2.829"/>
            <path d="M3.35 5.47q-.27.24-.518.487A13 13 0 0 0 1.172 8l.195.288c.335.48.83 1.12 1.465 1.755C4.121 11.332 5.881 12.5 8 12.5c.716 0 1.39-.133 2.02-.36l.77.772A7 7 0 0 1 8 13.5C3 13.5 0 8 0 8s.939-1.721 2.641-3.238l.708.709zm10.296 8.884-12-12 .708-.708 12 12z"/>
        </svg>`;

    togglePasswordBtn.addEventListener('click', () => {
        const isPassword = passwordInput.type === 'password';
        passwordInput.type = isPassword ? 'text' : 'password';
        toggleIcon.innerHTML = isPassword ? eyeOpen : eyeSlash;
    });
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
