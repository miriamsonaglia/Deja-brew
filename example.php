<?php

require_once __DIR__ . '/models/User.php';

try {
    // Test connessione
    echo "Testing database connection...\n";
    
    // Creare un nuovo utente
    $user = new User();
    $user->name = 'Mario Rossi';
    $user->email = 'mario@email.com';
    $user->password = 'password123';
    $user->save();
    
    echo "User created with ID: " . $user->id . "\n";
    
    // Oppure con create() (mass assignment)
    $user2 = User::create([
        'name' => 'Luigi Verdi',
        'email' => 'luigi@email.com',
        'password' => 'password456'
    ]);
    
    // Trovare un utente
    $foundUser = User::find(1);
    if ($foundUser) {
        echo "Found user: " . $foundUser->name . "\n";
    }
    
    // Query più complesse
    $users = User::where('name', 'like', '%Mario%')->get();
    foreach ($users as $user) {
        echo "User: " . $user->name . " - " . $user->email . "\n";
    }
    
    // Contare utenti
    $count = User::count();
    echo "Total users: " . $count . "\n";
    
    // Aggiornare
    User::where('id', 1)->update(['name' => 'Mario Updated']);
    
    // Eliminare
    // User::find(2)->delete();
    // User::destroy([3, 4, 5]); // elimina multipli
    
    // Query raw se necessario
    $users = User::whereRaw("datetime('now', '-1 day')")->get();
    
    // Debug queries (se abilitato nel bootstrap)
    $queries = Illuminate\Database\Capsule\Manager::getQueryLog();
    foreach ($queries as $query) {
        echo $query['query'] . "\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}