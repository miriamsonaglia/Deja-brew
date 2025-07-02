<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Registrazione - Deja-brew</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #FFFCD6;
        }

        .register-card {
            max-width: 450px;
            margin: 60px auto 100px auto;
            padding: 2rem;
            background-color: #BEFFC8;
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }

        .back-link {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            font-size: 0.95rem;
            font-weight: 500;
            color: #5900FF;
            cursor: pointer;
            text-decoration: underline;
            margin-bottom: 1.5rem;
        }

        .back-link svg {
            width: 20px;
            height: 20px;
            fill: #5900FF;
        }

        .register-card h2 {
            text-align: center;
            color: #692E01;
            margin-bottom: 1.8rem;
            font-weight: 700;
        }

        .form-row {
            display: flex;
            gap: 1rem;
        }

        .form-row > .form-group {
            flex: 1;
        }

        .role-radio {
            display: flex;
            justify-content: center;
            gap: 2rem;
            margin-top: 1rem;
            margin-bottom: 1.8rem;
        }

        .role-radio label {
            cursor: pointer;
            font-weight: 600;
            color: #5900FF;
        }

        .btn-submit {
            width: 100%;
            font-weight: 600;
        }
    </style>
</head>
<body>

    <div class="register-card">
        <a href="{{ route('login.form') }}" class="back-link" aria-label="Torna indietro alla pagina di login">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path d="M15.41 7.41 14 6l-6 6 6 6 1.41-1.41L10.83 12z"/>
            </svg>
            Hai già un account?
        </a>

        <h2>Registrazione</h2>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form method="POST" action="{{ route('register') }}">
            @csrf

            <div class="form-row mb-3">
                <div class="form-group">
                    <label for="nome">Nome</label>
                    <input type="text" 
                    class="form-control" 
                    id="nome" 
                    name="nome" 
                    required value="{{ old('nome') }}">
                </div>
                <div class="form-group">
                    <label for="cognome">Cognome</label>
                    <input type="text" 
                    class="form-control" 
                    id="cognome" 
                    name="cognome" 
                    required value="{{ old('cognome') }}">
                </div>
            </div>

            <div class="mb-3">
                <label for="username">Username</label>
                <input type="text" 
                class="form-control" 
                id="username" name="username" 
                required value="{{ old('username') }}">
            </div>

            <div class="mb-3">
                <label for="email">Email</label>
                <input type="email" 
                class="form-control" 
                id="email" 
                name="email" 
                required value="{{ old('email') }}">
            </div>

            <div class="form-row mb-3">
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" 
                    class="form-control" 
                    id="password" 
                    name="password" 
                    required>
                </div>
                <div class="form-group">
                    <label for="password_confirmation">Conferma Password</label>
                    <input type="password" 
                    class="form-control" 
                    id="password_confirmation" 
                    name="password_confirmation" 
                    required>
                </div>
            </div>

            <div class="role-radio" role="radiogroup" aria-label="Seleziona ruolo">
                <div>
                    <input type="radio" 
                    id="role_acquirente" 
                    name="role" 
                    value="acquirente" {{ old('role', 'acquirente') == 'acquirente' ? 'checked' : '' }}>
                    <label for="role_acquirente">Acquirente</label>
                </div>
                <div>
                    <input type="radio" 
                    id="role_venditore" 
                    name="role" 
                    value="venditore" {{ old('role') == 'venditore' ? 'checked' : '' }}>
                    <label for="role_venditore">Venditore</label>
                </div>
            </div>

            <button type="submit" 
            class="btn btn-primary btn-submit">Registrati</button>
        </form>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
