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
        Schema::create('manutenzioni_checklist_dinamiche', function (Blueprint $table) {
    $table->id();
    $table->foreignId('tipologia_id')->constrained('manutenzioni_tipologie')->onDelete('cascade');
    $table->json('contenuto'); // contiene array di voci in JSON
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manutenzioni_checklist_dinamiche');
    }
};
