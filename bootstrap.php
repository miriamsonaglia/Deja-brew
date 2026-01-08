<?php

// bootstrap.php
// Configurazione Eloquent standalone (senza Laravel) - Database SQLite nella root

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

// === Definizione degli helper necessari (per evitare errori come base_path()) ===
if (!function_exists('base_path')) {
    function base_path($path = '')
    {
        return __DIR__ . ($path ? DIRECTORY_SEPARATOR . ltrim($path, DIRECTORY_SEPARATOR) : '');
    }
}

// === Inizializzazione di Capsule (Eloquent) ===
$capsule = new Capsule;

// === Connessione al database SQLite nella root del progetto ===
$capsule->addConnection([
    'driver'                  => 'sqlite',
    'database'                => __DIR__ . '/database.sqlite',  // File direttamente nella root
    'prefix'                  => '',
    'foreign_key_constraints' => true,  // Importante per far rispettare le foreign key
]);

/*
// Se un giorno vorrai passare a MySQL, decommenta questa parte:
/*
$capsule->addConnection([
    'driver'    => 'mysql',
    'host'      => '127.0.0.1',
    'port'      => '3306',
    'database'  => 'deja_brew',
    'username'  => 'root',
    'password'  => '',
    'charset'   => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
    'prefix'    => '',
]);
*/

// Rendi la connessione globale
$capsule->setAsGlobal();

// Avvia Eloquent
$capsule->bootEloquent();

// (Opzionale) Log delle query per debug
// $capsule->getConnection()->enableQueryLog();

echo "Eloquent avviato correttamente! Database: " . __DIR__ . "/database.sqlite\n";

return $capsule;