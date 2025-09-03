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
        Schema::create('manutenzioni_registro', function (Blueprint $table) {
    $table->id();
    $table->foreignId('attrezzatura_id')->constrained('attrezzature')->onDelete('cascade');
    $table->foreignId('tipologia_id')->constrained('manutenzioni_tipologie')->onDelete('cascade');
    $table->date('data_esecuzione');
    $table->foreignId('competenza_id')->nullable()->constrained('manutenzioni_competenze')->onDelete('set null');
    $table->string('esito')->nullable(); // esempio: OK, da riparare, fuori uso, ecc.
    $table->string('documento_verifica')->nullable(); // link o path PDF verbale
    $table->text('note')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manutenzioni_registro');
    }
};
