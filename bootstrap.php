<?php

require_once __DIR__ . '/vendor/autoload.php';

use Illuminate\Database\Capsule\Manager as Capsule;

$capsule = new Capsule;

// Configurazione del database
/*
$capsule->addConnection([
    'driver' => 'mysql', // mysql, pgsql, sqlite, sqlsrv
    'host' => 'localhost',
    'database' => 'nome_database',
    'username' => 'username',
    'password' => 'password',
    'charset' => 'utf8',
    'collation' => 'utf8_unicode_ci',
    'prefix' => '',
]);
*/

// Per SQLite usa invece:

$capsule->addConnection([
    'driver' => 'sqlite',
    'database' => __DIR__ . '/database.sqlite',
    'prefix' => '',
]);

// Rendi Eloquent disponibile globalmente
$capsule->setAsGlobal();

// Avvia Eloquent
$capsule->bootEloquent();

// Opzionale: abilita query logging per debug
$capsule->connection()->enableQueryLog();

return $capsule;