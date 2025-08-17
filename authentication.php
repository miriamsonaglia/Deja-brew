<?php
// FIXME: SE VEDI ROSSO E' PERCHÉ TI MANCANO LE CLAUSOLE "USE"
require_once __DIR__ . '/../Models/Utente.php';
require_once __DIR__ . '/../Models/UtenteCompratore.php';
require_once __DIR__ . '/../Models/UtenteVenditore.php';
require_once __DIR__ . '/../role.php';

use Role;
use App\Models\Utente;
use App\Models\UtenteCompratore;
use App\Models\UtenteVenditore;

session_start();

class AuthController
{
    public function login()
    {
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';
        $role = $_POST['role'] ?? '';

        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || !$password || !in_array($role, ['acquirente', 'venditore'])) {
            $_SESSION['error'] = "Dati non validi.";
            header('Location: /login.php');
            exit;
        }

        $utente = Utente::where('email', $email)->first();

        if ($utente && password_verify($password, $utente->password)) {
            $_SESSION['user'] = $utente->toArray();

            if ($role === 'acquirente') {
                if (!$utente->utenteCompratore) {
                    UtenteCompratore::create(['id_utente' => $utente->id]);
                }
                $_SESSION['UserRole'] = Role::BUYER;
            } else {
                if (!$utente->utenteVenditore) {
                    UtenteVenditore::create(['id_utente' => $utente->id]);
                }
                $_SESSION['UserRole'] = Role::VENDOR;
            }

            // Una sola home dinamica
            header('Location: /home.php');
            exit;
        }

        $_SESSION['error'] = 'Credenziali errate.';
        header('Location: /login.php');
        exit;
    }

    public function register()
    {
        $data = $_POST;
        $errors = [];

        if (empty($data['nome']) || empty($data['cognome']) || empty($data['username']) || empty($data['email']) || empty($data['password']) || empty($data['role'])) {
            $errors[] = 'Tutti i campi sono obbligatori.';
        }

        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email non valida.';
        }

        if ($data['password'] !== ($_POST['password_confirmation'] ?? '')) {
            $errors[] = 'Le password non corrispondono.';
        }

        if (Utente::where('email', $data['email'])->exists()) {
            $errors[] = 'Email già registrata.';
        }

        if (Utente::where('username', $data['username'])->exists()) {
            $errors[] = 'Username già registrato.';
        }

        if (!in_array($data['role'], ['acquirente', 'venditore'])) {
            $errors[] = 'Ruolo non valido.';
        }

        if ($errors) {
            $_SESSION['errors'] = $errors;
            $_SESSION['old'] = $data;
            header('Location: /register.php');
            exit;
        }

        $utente = Utente::create([
            'nome' => $data['nome'],
            'cognome' => $data['cognome'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => password_hash($data['password'], PASSWORD_DEFAULT),
        ]);

        $_SESSION['user'] = $utente->toArray();

        if ($data['role'] === 'acquirente') {
            UtenteCompratore::create(['id_utente' => $utente->id]);
            $_SESSION['UserRole'] = Role::BUYER;
        } else {
            UtenteVenditore::create(['id_utente' => $utente->id]);
            $_SESSION['UserRole'] = Role::VENDOR;
        }

        // Una sola home dinamica
        header('Location: /home.php');
        exit;
    }

    public function logout()
    {
        session_destroy();
        header('Location: /index.php');
        exit;
    }
}

$auth = new AuthController();
$action = $_GET['action'] ?? '';

switch ($action) {
    case 'login':
        $auth->login();
        break;
    case 'register':
        $auth->register();
        break;
    case 'logout':
        $auth->logout();
        break;
    default:
        http_response_code(404);
        echo "Azione non valida.";
        break;
}
