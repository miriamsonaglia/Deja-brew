<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ordine', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_utente')
                  ->constrained('utenti')
                  ->onDelete('cascade');

            $table->foreignId('id_prodotto')
                  ->constrained('prodotti')
                  ->onDelete('cascade');

            $table->enum('status', ['in_attesa', 'in_lavorazione', 'spedito', 'consegnato', 'annullato'])
                  ->default('in_attesa');

            $table->decimal('prezzo_totale', 8, 2);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ordine');
    }
};

?>