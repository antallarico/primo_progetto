<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('estintori_info', function (Blueprint $table) {
            $table->id();
            $table->foreignId('attrezzatura_id')->constrained('attrezzature')->onDelete('cascade');
            $table->string('tipologia')->nullable(); // CO2, polvere, schiuma, ecc.
            $table->integer('capacita_kg')->nullable(); // in kg
            $table->boolean('mobile')->default(false); // estintore carrellato
            $table->date('data_collocazione')->nullable();
            $table->string('ubicazione_dettaglio')->nullable();
            $table->string('norma_riferimento')->nullable(); // es. UNI 9994-1
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('estintori_info');
    }
};
