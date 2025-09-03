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
        Schema::create('manutenzioni_componenti_sostituiti', function (Blueprint $table) {
    $table->id();
    $table->foreignId('registro_id')->constrained('manutenzioni_registro')->onDelete('cascade');
    $table->string('nome_componente');
    $table->integer('quantita')->default(1);
    $table->text('note')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manutenzioni_componenti_sostituiti');
    }
};
