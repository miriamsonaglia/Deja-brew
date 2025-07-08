<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AuthController extends Controller
{
    // Mostra il form di registrazione
    public function showRegisterForm()
    {
        return view('register'); // Assumendo resources/views/auth/register.blade.php
    }

    // Gestisce la registrazione
    public function register(Request $request)
    {
        // Validazione dati
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()], 
            'role' => ['required', 'in:acquirente,venditore'],
        ]);

        // Crea utente
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'],
        ]);

        // Effettua il login automatico
        auth()->login($user);

        // Reindirizza dopo registrazione
        return redirect('/dashboard');
    }
}
