<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    // Mostra la pagina di login
    public function showLoginForm()
    {
        return view('login'); // Assumendo che il file sia resources/views/auth/login.blade.php
    }

    // Gestisce la richiesta POST di login
    public function login(Request $request)
    {
        // Validazione dati input
        $credentials = $request->validate([
            'email' => ['required', 'email'],
            'password' => ['required'],
            'role' => ['required', 'in:acquirente,venditore'],  // controlla che il ruolo sia uno di questi due
        ]);

        // Aggiungi la condizione sul ruolo nel tentativo di login
        // Assumiamo che la tabella utenti abbia una colonna 'role' con valori 'acquirente' o 'venditore'

        if (Auth::attempt([
            'email' => $credentials['email'],
            'password' => $credentials['password'],
            'role' => $credentials['role'],
        ])) {
            // Login riuscito
            $request->session()->regenerate();

            // Reindirizza dove vuoi, ad esempio home o dashboard in base al ruolo
            return redirect()->intended('/dashboard');
        }

        // Login fallito, torna indietro con errore
        return back()->withErrors([
            'email' => 'Le credenziali non sono corrette o il ruolo non corrisponde.',
        ])->onlyInput('email');
    }

    // Facoltativo: logout
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
