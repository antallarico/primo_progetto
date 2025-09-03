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
        Schema::create('manutenzioni_registro_voci', function (Blueprint $table) {
    $table->id();
    $table->foreignId('registro_id')->constrained('manutenzioni_registro')->onDelete('cascade');
    $table->string('voce'); // testo della voce checklist
    $table->string('esito')->nullable(); // es. ok, non ok, n/a
    $table->text('note')->nullable();
    $table->timestamps();
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('manutenzioni_registro_voci');
    }
};
