<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('prodotto', function (Blueprint $table) {
            $table->id();

            $table->string('nome');
            $table->string('tipo');
            $table->decimal('prezzo', 8, 2);
            $table->string('intensita')->nullable();
            $table->string('fotografia')->nullable();
            $table->string('provenienza')->nullable();
            $table->decimal('peso', 6, 3);

            $table->foreignId('id_venditore')
                  ->constrained('utenti_venditori')
                  ->onDelete('cascade');

            $table->foreignId('categoria_id')
                  ->constrained('categorie')
                  ->onDelete('set null')
                  ->nullable();

            $table->foreignId('aroma_id')
                  ->constrained('aromi')
                  ->onDelete('set null')
                  ->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('prodotto');
    }
};
