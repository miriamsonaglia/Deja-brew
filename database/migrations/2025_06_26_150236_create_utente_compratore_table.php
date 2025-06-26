<?php

    use Illuminate\Database\Migrations\Migration;
    use Illuminate\Database\Schema\Blueprint;
    use Illuminate\Support\Facades\Schema;

    class CreateUtenteCompratoreTable extends Migration
    {
        /**
         * Esegui la migrazione.
         */
        public function up(): void
        {
            Schema::create('utenteCompratore', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('id_utente');
                $table->timestamps();

                $table->foreign('id_utente')->references('id')->on('utente')->onDelete('cascade');
            });
        }

        /**
         * Annulla la migrazione.
         */
        public function down(): void
        {
            Schema::dropIfExists('utenteCompratore');
        }
    }
?>