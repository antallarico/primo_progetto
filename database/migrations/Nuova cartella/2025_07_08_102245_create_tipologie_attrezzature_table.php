<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tipologie_attrezzature', function (Blueprint $table) {
    $table->id();
    $table->string('nome');
    $table->boolean('has_scheda')->default(false); // indica se ha una scheda tecnica dedicata
    $table->string('scheda_tabella')->nullable();  // es. "estintori_info"
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tipologie_attrezzature');
    }
};
