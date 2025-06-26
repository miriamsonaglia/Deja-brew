<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('lista', function (Blueprint $table) {
            $table->id();

            $table->foreignId('id_utente_compratore')
                  ->constrained('utenti_compratori')
                  ->onDelete('cascade');

            $table->foreignId('id_prodotto')
                  ->constrained('prodotto')
                  ->onDelete('cascade');

            $table->enum('tipo', ['desideri', 'carrello']);
            $table->unsignedInteger('quantita')->default(1);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('lista');
    }
};
