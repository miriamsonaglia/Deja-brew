<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImpostazioniUtenteTable extends Migration
{
    /**
     * Esegui la migrazione.
     */
    public function up(): void
    {
        Schema::create('impostazioniUtente', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_utente');
            $table->string('tema')->nullable();
            $table->boolean('notifiche')->default(true);
            $table->timestamps();

            $table->foreign('id_utente')->references('id')->on('utente')->onDelete('cascade');
        });
    }

    /**
     * Annulla la migrazione.
     */
    public function down(): void
    {
        Schema::dropIfExists('impostazioniUtente');
    }
}
