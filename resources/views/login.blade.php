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
            background-color: #FFFCD6;
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4" style="max-width: 450px; width: 100%; background-color: #BEFFC8; border-radius: 1rem;">

            <h1 class="text-center mb-4 fw-bold" style="color: #594431;">Deja-brew</h1>

            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" class="form-control" id="email" name="email" required>
                </div>

                <div class="mb-3">
                    <label for="password" class="form-label fw-semibold">Password</label>
                    <input type="password" class="form-control" id="password" name="password" required>
                </div>

                <input type="hidden" id="role" name="role" value="acquirente">

                <div class="d-grid mb-2">
                    <button type="submit" class="btn btn-info fw-semibold">
                        Accedi come <span id="role-label">acquirente</span>
                    </button>
                </div>

                <div class="text-center">
                    <button type="button" class="btn btn-link p-0 text-decoration-underline" onclick="toggleRole()">
                        Cambia ruolo in venditore
                    </button>
                </div>
            </form>

            <div class="text-center mt-3 small fw-bold">
                Non sei ancora uno di noi?<br>
                <a href="{{ route('register.form') }}" class="text-decoration-underline text-primary fw-semibold">Registrati</a><br>
            </div>

            <div class="text-center mt-1 small">
                <span>oppure</span><br>
                <a href="{{ route('guest.home') }}" class="text-decoration-underline text-primary fw-semibold">Prosegui senza registrarti</a>
            </div>
        </div>
    </div>

    <script>
        let currentRole = 'acquirente';

        function toggleRole() {
            currentRole = currentRole === 'acquirente' ? 'venditore' : 'acquirente';
            document.getElementById('role').value = currentRole;
            document.getElementById('role-label').innerText = currentRole;
            document.querySelector('.btn-link').innerText = `Sono un ${currentRole === 'acquirente' ? 'venditore' : 'acquirente'}`;
        }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
