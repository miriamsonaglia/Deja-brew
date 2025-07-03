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
            background-color: #FFFCD6;
        }
    </style>
</head>
<body>

    <div class="container d-flex justify-content-center align-items-center min-vh-100">
        <div class="card shadow p-4" style="max-width: 500px; width: 100%; background-color: #BEFFC8; border-radius: 1rem;">

            <!-- Link indietro -->
            <div class="mb-3">
                <a href="{{ route('login.form') }}" class="text-decoration-underline d-inline-flex align-items-center text-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" fill="#5900FF" viewBox="0 0 24 24" class="me-1">
                        <path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
                    </svg>
                    Hai già un account?
                </a>
            </div>

            <!-- Titolo -->
            <h2 class="text-center text-brown mb-4 fw-bold" style="color: #692E01;">Registrazione</h2>

            <!-- Errori -->
            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Form -->
            <form method="POST" action="{{ route('register') }}">
                @csrf

                <div class="row mb-3">
                    <div class="col">
                        <label for="nome" class="form-label fw-semibold">Nome</label>
                        <input type="text" id="nome" name="nome" class="form-control" required value="{{ old('nome') }}">
                    </div>
                    <div class="col">
                        <label for="cognome" class="form-label fw-semibold">Cognome</label>
                        <input type="text" id="cognome" name="cognome" class="form-control" required value="{{ old('cognome') }}">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="username" class="form-label fw-semibold">Username</label>
                    <input type="text" id="username" name="username" class="form-control" required value="{{ old('username') }}">
                </div>

                <div class="mb-3">
                    <label for="email" class="form-label fw-semibold">Email</label>
                    <input type="email" id="email" name="email" class="form-control" required value="{{ old('email') }}">
                </div>

                <div class="row mb-3">
                    <div class="col">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <input type="password" id="password" name="password" class="form-control" required>
                    </div>
                    <div class="col">
                        <label for="password_confirmation" class="form-label fw-semibold">Conferma Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
                    </div>
                </div>

                <!-- Selezione ruolo -->
                <div class="text-center mb-4">
                    <label class="form-label fw-semibold d-block mb-2">Seleziona il tuo ruolo</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="role" id="role_acquirente" value="acquirente" {{ old('role', 'acquirente') == 'acquirente' ? 'checked' : '' }}>
                        <label class="form-check-label text-primary fw-semibold" for="role_acquirente">Acquirente</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="role" id="role_venditore" value="venditore" {{ old('role') == 'venditore' ? 'checked' : '' }}>
                        <label class="form-check-label text-primary fw-semibold" for="role_venditore">Venditore</label>
                    </div>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-info fw-semibold">Registrati</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
