<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cartaDiCredito', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_utente');
            $table->string('circuito_pagamento');
            $table->string('codice_carta');
            $table->string('cvv_carta');
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('id_utente')
                  ->references('id')->on('utenti')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cartaDiCredito');
    }
};
