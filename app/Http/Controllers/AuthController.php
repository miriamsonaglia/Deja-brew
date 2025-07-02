<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Utente;

class AuthController extends Controller
{
    // MOSTRO LA PAGINA DI LOGIN
    public function showLoginForm()
    {
        return view('login'); // resources/views/login.blade.php
    }

    // GESTISCO IL LOGIN
    public function login(Request $request)
    {
        // riceve i dati inviati dal form di login (email, psw e ruolo)
        // controlla che il formato dei dati sia corretto e che il ruolo ci sia
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required', 'in:acquirente,venditore'],
        ]);

        // Provo ad accedere verificando mail e psw
            // se ha successo rigenera la sessione per motivi di sicurezza
            // reindirizza l'utente verso la pagina corrispondente (homeAcquirente/Venditore/Guest)
        if (Auth::attempt([
            'email' => $credentials['email'], 
            'password' => $credentials['password']
        ])) {
            $request->session()->regenerate();

            // Controllo il ruolo selezionato
            if ($credentials['role'] === 'acquirente') {
                return redirect()->intended('/homeAcquirente'); //NOME DA CAMBIARE CON QUELLO EFFETTIVO
            } else {
                return redirect()->intended('/homeVenditore'); // NOME DA CAMBIARE CON QUELLO EFFETTIVO
            }
        }

        // Login fallito, errore
        // torna indietro con un messaggio di errore e conserva i dati inseriti (tranne la psw)
        return back()->withErrors([
            'email' => 'Credenziali non valide o ruolo errato.',
        ])->withInput();
    }

    // PAGINA DI REGISTRAZIONE
    public function showRegisterForm()
    {
        return view('register'); // resources/views/register.blade.php
    }

    // GESTISCO LA REGISTRAZIONE
    public function register(Request $request)
    {
        // Riceve i dati dal form di registrazione e li valida
        $data = $request->validate([
            'nome' => 'required|string|max:255',
            'cognome' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:utente,username',
            'email' => 'required|string|email|max:255|unique:utente,email',
            'password' => 'required|string|min:8|confirmed', // password_confirmation serve
            'role' => 'required|in:acquirente,venditore',
        ]);

        // Creazione utente nel database con hashing password automatico (grazie a $casts nel modello)
        $utente = Utente::create([
            'nome' => $data['nome'],
            'cognome' => $data['cognome'],
            'username' => $data['username'],
            'email' => $data['email'],
            'password' => $data['password'],
        ]);

        // Redirect a dashboard o home (DA FARE)
        return redirect('/dashboard');
    }

}
