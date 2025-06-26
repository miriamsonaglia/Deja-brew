<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateFatturaTable extends Migration
    {
        /**
         * Esegui la migrazione.
         */
        public function up(): void
        {
            Schema::create('fattura', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_utente');
                $table->unsignedBigInteger('id_venditore');
                $table->unsignedBigInteger('id_ordine');
                $table->timestamps();

                $table->foreign('id_utente')->references('id')->on('utente')->onDelete('cascade');
                $table->foreign('id_venditore')->references('id')->on('utenteVenditore')->onDelete('cascade');
                $table->foreign('id_ordine')->references('id')->on('ordini')->onDelete('cascade');
            });
        }

        /**
         * Annulla la migrazione.
         */
        public function down(): void
        {
            Schema::dropIfExists('fattura');
        }
    }
?>