<?php

require_once __DIR__ . '/bootstrap.php';

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Capsule\Manager as Capsule;

// Migration per creare la tabella utente
function createUtenteTable()
{
    Capsule::schema()->create('utente', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('cognome');
        $table->string('email')->unique();
        $table->string('username')->unique();
        $table->string('password');
        $table->timestamp('email_verified_at')->nullable();
        $table->rememberToken();
        // $table->timestamps(); // Rimosso per disabilitare created_at e updated_at
    });
    
    echo "Utente table created successfully!\n";
}

// Migration per creare la tabella utenteCompratore
function createUtenteCompratoreTable()
{
    Capsule::schema()->create('utenteCompratore', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_utente')->constrained('utente')->onDelete('cascade');
        // $table->timestamps(); // Rimosso per disabilitare created_at e updated_at
    });
    
    echo "UtenteCompratore table created successfully!\n";
}

// Migration per creare la tabella utenteVenditore
function createUtenteVenditoreTable()
{
    Capsule::schema()->create('utenteVenditore', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_utente')->constrained('utente')->onDelete('cascade');
        $table->text('descrizione')->nullable();
        // $table->timestamps(); // Rimosso per disabilitare created_at e updated_at
    });
    
    echo "UtenteVenditore table created successfully!\n";
}

// Migration per creare la tabella categoria
function createCategoriaTable()
{
    Capsule::schema()->create('categoria', function (Blueprint $table) {
        $table->id();
        $table->string('descrizione');
        // $table->timestamps(); // Rimosso per disabilitare created_at e updated_at
    });
    
    echo "Categoria table created successfully!\n";
}

// Migration per creare la tabella aroma
function createAromaTable()
{
    Capsule::schema()->create('aroma', function (Blueprint $table) {
        $table->id();
        $table->string('gusto');
        // $table->timestamps(); // Rimosso per disabilitare created_at e updated_at
    });
    
    echo "Aroma table created successfully!\n";
}

// Migration per creare la tabella prodotto
function createProdottoTable()
{
    Capsule::schema()->create('prodotto', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('tipo');
        $table->decimal('prezzo', 10, 2);
        $table->string('intensita')->nullable();
        $table->string('fotografia')->nullable();
        $table->string('provenienza')->nullable();
        $table->decimal('peso', 8, 3)->nullable();
        $table->foreignId('id_venditore')->constrained('utenteVenditore')->onDelete('cascade');
        $table->foreignId('categoria_id')->constrained('categoria')->onDelete('cascade');
        $table->foreignId('aroma_id')->constrained('aroma')->onDelete('cascade');
        // $table->timestamps(); // Rimosso per disabilitare created_at e updated_at
    });
    
    echo "Prodotto table created successfully!\n";
}

// Migration per creare la tabella ordine
function createOrdineTable()
{
    Capsule::schema()->create('ordine', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_utente')->constrained('utente')->onDelete('cascade');
        $table->foreignId('id_prodotto')->constrained('prodotto')->onDelete('cascade');
        $table->string('status')->default('pending');
        $table->decimal('prezzo_totale', 10, 2);
        // $table->timestamps(); // Rimosso per disabilitare created_at e updated_at
    });
    
    echo "Ordine table created successfully!\n";
}

// Migration per creare la tabella recensione
function createRecensioneTable()
{
    Capsule::schema()->create('recensione', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_utente')->constrained('utente')->onDelete('cascade');
        $table->foreignId('id_prodotto')->constrained('prodotto')->onDelete('cascade');
        $table->tinyInteger('stelle')->unsigned()->default(1);
        // $table->timestamps(); // Rimosso per disabilitare created_at e updated_at
    });
    
    echo "Recensione table created successfully!\n";
}

// Migration per creare la tabella lista (carrello e desideri)
function createListaTable()
{
    Capsule::schema()->create('lista', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_utente_compratore')->constrained('utenteCompratore')->onDelete('cascade');
        $table->foreignId('id_prodotto')->constrained('prodotto')->onDelete('cascade');
        $table->enum('tipo', ['desideri', 'carrello']);
        $table->integer('quantita')->unsigned()->default(1);
        // $table->timestamps(); // Rimosso per disabilitare created_at e updated_at
        
        // Indice unico per evitare duplicati
        $table->unique(['id_utente_compratore', 'id_prodotto', 'tipo']);
    });
    
    echo "Lista table created successfully!\n";
}

// Migration per creare la tabella fattura
function createFatturaTable()
{
    Capsule::schema()->create('fattura', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_utente')->constrained('utente')->onDelete('cascade');
        $table->foreignId('id_venditore')->constrained('utenteVenditore')->onDelete('cascade');
        $table->foreignId('id_ordine')->constrained('ordine')->onDelete('cascade');
        // $table->timestamps(); // Rimosso per disabilitare created_at e updated_at
    });
    
    echo "Fattura table created successfully!\n";
}

// Migration per creare la tabella cartaDiCredito
function createCartaDiCreditoTable()
{
    Capsule::schema()->create('cartaDiCredito', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_utente')->constrained('utente')->onDelete('cascade');
        $table->string('circuito_pagamento'); // Visa, MasterCard, etc.
        $table->string('codice_carta'); // Criptato
        $table->string('cvv_carta'); // Criptato
        // $table->timestamps(); // Rimosso per disabilitare created_at e updated_at
    });
    
    echo "CartaDiCredito table created successfully!\n";
}

// Migration per creare la tabella impostazioniUtente
function createImpostazioniUtenteTable()
{
    Capsule::schema()->create('impostazioniUtente', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_utente')->unique()->constrained('utente')->onDelete('cascade');
        $table->string('tema')->default('light'); // light, dark, etc.
        $table->boolean('notifiche')->default(true);
        // $table->timestamps(); // Rimosso per disabilitare created_at e updated_at
    });
    
    echo "ImpostazioniUtente table created successfully!\n";
}

// Migration per creare la tabella notifica
function createNotificaTable()
{
    Capsule::schema()->create('notifica', function (Blueprint $table) {
        $table->id();
        $table->string('tipo');
        $table->boolean('impostazione')->default(true);
        // $table->timestamps(); // Rimosso per disabilitare created_at e updated_at
    });
    
    echo "Notifica table created successfully!\n";
}

// Array delle tabelle nell'ordine corretto per rispettare le foreign key
$migrations = [
    'utente' => 'createUtenteTable',
    'utenteCompratore' => 'createUtenteCompratoreTable',
    'utenteVenditore' => 'createUtenteVenditoreTable',
    'categoria' => 'createCategoriaTable',
    'aroma' => 'createAromaTable',
    'prodotto' => 'createProdottoTable',
    'ordine' => 'createOrdineTable',
    'recensione' => 'createRecensioneTable',
    'lista' => 'createListaTable',
    'fattura' => 'createFatturaTable',
    'cartaDiCredito' => 'createCartaDiCreditoTable',
    'impostazioniUtente' => 'createImpostazioniUtenteTable',
    'notifica' => 'createNotificaTable',
];

// Esegui le migrations
try {
    echo "Starting database migrations...\n";
    echo "================================\n";
    
    foreach ($migrations as $tableName => $functionName) {
        if (!Capsule::schema()->hasTable($tableName)) {
            echo "Creating table: $tableName\n";
            $functionName();
        } else {
            echo "Table $tableName already exists, skipping...\n";
        }
    }
    
    echo "================================\n";
    echo "All migrations completed successfully!\n";
    
} catch (Exception $e) {
    echo "Migration error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}