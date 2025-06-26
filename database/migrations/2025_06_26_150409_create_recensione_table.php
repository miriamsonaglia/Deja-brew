<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recensione', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_utente')
                  ->constrained('utenti')
                  ->onDelete('cascade');

            $table->foreignId('id_prodotto')
                  ->constrained('prodotto')
                  ->onDelete('cascade');

            $table->unsignedTinyInteger('stelle'); // valori da 1 a 5

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recensione');
    }
};
