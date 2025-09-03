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
        Schema::create('manutenzioni_tipologie', function (Blueprint $table) {
    $table->id();
    $table->string('nome');
    $table->text('descrizione')->nullable();
    $table->integer('periodicita_mesi')->nullable(); // può essere null se non è periodica
    $table->boolean('obbligatoria')->default(false);
    $table->boolean('con_checklist')->default(false);
    $table->boolean('documentabile')->default(false); // se serve verbale o altro documento
    $table->text('note')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manutenzioni_tipologie');
    }
};
