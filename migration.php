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
        $table->string('immagine_profilo')->nullable(); // Campo aggiunto
        $table->timestamp('email_verified_at')->nullable();
        $table->rememberToken();
        // $table->timestamps(); // Disabilitati come da progetto
    });

    echo "Utente table created successfully!\n";
}

// Migration per creare la tabella utenteCompratore
function createUtenteCompratoreTable()
{
    Capsule::schema()->create('utenteCompratore', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_utente')->constrained('utente')->onDelete('cascade');
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
        $table->string('paese')->nullable();        // Campo aggiunto
        $table->string('cellulare')->nullable();    // Campo aggiunto
    });

    echo "UtenteVenditore table created successfully!\n";
}

// Migration per creare la tabella categoria
function createCategoriaTable()
{
    Capsule::schema()->create('categoria', function (Blueprint $table) {
        $table->id();
        $table->string('descrizione');
    });

    echo "Categoria table created successfully!\n";
}

// Migration per creare la tabella aroma
function createAromaTable()
{
    Capsule::schema()->create('aroma', function (Blueprint $table) {
        $table->id();
        $table->string('gusto');
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
        $table->string('status');
        $table->decimal('prezzo_totale', 10, 2);
        $table->unsignedInteger('quantita')->default(1); // Campo aggiunto
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
        $table->unsignedTinyInteger('stelle'); // 1-5
        $table->text('testo')->nullable();
    });

    echo "Recensione table created successfully!\n";
}

// Migration per creare la tabella lista
function createListaTable()
{
    Capsule::schema()->create('lista', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_utente_compratore')->constrained('utenteCompratore')->onDelete('cascade');
        $table->foreignId('id_prodotto')->constrained('prodotto')->onDelete('cascade');
        $table->enum('tipo', ['desideri', 'carrello']);
        $table->unsignedInteger('quantita')->default(1);
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
        $table->foreignId('id_ordine')->nullable()->constrained('ordine')->onDelete('set null');
        $table->string('transaction_id')->nullable(); // Campo aggiunto per gestire acquisti multi-prodotto
    });

    echo "Fattura table created successfully!\n";
}

// Migration per creare la tabella cartaDiCredito
function createCartaDiCreditoTable()
{
    Capsule::schema()->create('cartaDiCredito', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_utente')->constrained('utente')->onDelete('cascade');
        $table->string('circuito_pagamento');
        $table->string('codice_carta');
        $table->string('cvv_carta');
        $table->string('nome_titolare');              // Campo aggiunto
        $table->unsignedTinyInteger('scadenza_mese'); // 1-12
        $table->unsignedSmallInteger('scadenza_anno');
    });

    echo "CartaDiCredito table created successfully!\n";
}

// Migration per creare la tabella impostazioniUtente
function createImpostazioniUtenteTable()
{
    Capsule::schema()->create('impostazioniUtente', function (Blueprint $table) {
        $table->id();
        $table->foreignId('id_utente')->unique()->constrained('utente')->onDelete('cascade');
        $table->string('tema')->default('light');
        $table->boolean('notifiche')->default(true);
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
    });

    echo "Notifica table created successfully!\n";
}

// Nuova migration per la tabella tipo_notifica
function createTipoNotificaTable()
{
    Capsule::schema()->create('tipo_notifica', function (Blueprint $table) {
        $table->id();
        $table->string('descrizione')->unique();
    });

    echo "TipoNotifica table created successfully!\n";
}

// Array delle migrazioni nell'ordine corretto (rispettando le foreign key)
$migrations = [
    'utente'              => 'createUtenteTable',
    'utenteCompratore'    => 'createUtenteCompratoreTable',
    'utenteVenditore'     => 'createUtenteVenditoreTable',
    'categoria'           => 'createCategoriaTable',
    'aroma'               => 'createAromaTable',
    'prodotto'            => 'createProdottoTable',
    'ordine'              => 'createOrdineTable',
    'recensione'          => 'createRecensioneTable',
    'lista'               => 'createListaTable',
    'fattura'             => 'createFatturaTable',
    'cartaDiCredito'      => 'createCartaDiCreditoTable',
    'impostazioniUtente'  => 'createImpostazioniUtenteTable',
    'notifica'            => 'createNotificaTable',
    'tipo_notifica'       => 'createTipoNotificaTable', // Nuova tabella
];

// Esecuzione delle migrazioni
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