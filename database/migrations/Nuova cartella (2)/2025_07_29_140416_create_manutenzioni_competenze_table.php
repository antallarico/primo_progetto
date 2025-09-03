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
        Schema::create('manutenzioni_competenze', function (Blueprint $table) {
    $table->id();
    $table->string('nome');
    $table->enum('tipo', ['interno', 'esterno']);
    $table->string('contatti')->nullable();
    $table->text('abilitazioni')->nullable(); // es. PES/PAV, INAIL, ecc.
    $table->text('note')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manutenzioni_competenze');
    }
};
