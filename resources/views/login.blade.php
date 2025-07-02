<!doctype html>
<html lang="it">
<head>
    <meta charset="UTF-8"> <!--CARATTERE-->
    <title>Login - Deja-brew</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"> <!--IMPORTO BOOTSTRAP 5.3-->
    
    <!--INIZIO CSS-->
    <style>
        body {
            background-color: #FFFCD6;
        }

        .login-card {
            max-width: 450px;
            margin: 80px auto;
            padding: 2rem;
            background-color: #BEFFC8;
            border-radius: 1rem;
            box-shadow: 0 4px 16px rgba(0,0,0,0.1);
        }

        .brand-title {
            font-weight: 700;
            font-size: 1.8rem;
            text-align: center;
            margin-bottom: 1.5rem;
            color: #692E01;
        }

        .toggle-role-btn {
            border: none;
            background: none;
            color: #5900FF;
            text-decoration: underline;
            cursor: pointer;
            padding: 0;
        }

        .subtext {
            text-align: center;
            margin-top: 1rem;
            font-size: 0.9rem;
        }

        .subtext a {
            color: #5900FF;
            text-decoration: underline;
        }

        .subtext a:hover {
            text-decoration: underline;
        }
    </style>
    <!--FINE CSS-->
    
</head>
<body>

    <div class="login-card">
        <div class="brand-title">Deja-brew</div>

        <!--MESSAGGIO ERRORE LARAVEL-->
        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <form method="POST" action="{{ route('login') }}">
            @csrf

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input
                    type="email"
                    class="form-control"
                    id="email"
                    name="email"
                    required
                >
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input
                    type="password"
                    class="form-control"
                    id="password"
                    name="password"
                    required
                >
            </div>

            <!-- Hidden field to hold role -->
            <input type="hidden" id="role" name="role" value="acquirente">

            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary">
                    Accedi come <span id="role-label">acquirente</span>
                </button>
            </div>

            <div class="text-center mt-2">
                <!--JS PER CAMBIARE LA SCRITTA-->
                <button type="button" class="toggle-role-btn" onclick="toggleRole()">Cambia ruolo in venditore</button>
            </div>
        </form>

        <div class="subtext mt-4">
            Non sei ancora uno di noi?<br>
            <a href="{{ route('register.form') }}">Registrati</a> <br>
            <span>oppure</span>
        </div>

        <div class="subtext mt-1">
            <a href="{{ route('guest.home') }}">Prosegui senza registrarti</a>
        </div>
    </div>

    <!--SCRIPT JS MEMORIZZA IL RUOLO E LO CAMBIA-->
    <script>
        let currentRole = 'acquirente';

        function toggleRole() {
            currentRole = (currentRole === 'acquirente') ? 'venditore' : 'acquirente';
            document.getElementById('role').value = currentRole;
            document.getElementById('role-label').innerText = currentRole;
            const toggleBtn = document.querySelector('.toggle-role-btn');
            toggleBtn.innerText = `Sono un ${currentRole === 'acquirente' ? 'venditore' : 'acquirente'}`;
        }
    </script>

    <!--FA FUNZIONARE I COMPONENTI DINAMICI DI BOOTSTRAP-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
