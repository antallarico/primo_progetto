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
        Schema::create('manutenzioni_programmate', function (Blueprint $table) {
    $table->id();
    $table->foreignId('attrezzatura_id')->constrained('attrezzature')->onDelete('cascade');
    $table->foreignId('tipologia_id')->constrained('manutenzioni_tipologie')->onDelete('cascade');
    $table->date('data_scadenza');
    $table->foreignId('competenza_id')->nullable()->constrained('manutenzioni_competenze')->onDelete('set null');
    $table->enum('stato', ['prevista', 'in ritardo', 'completata'])->default('prevista');
    $table->text('note')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manutenzioni_programmate');
    }
};
