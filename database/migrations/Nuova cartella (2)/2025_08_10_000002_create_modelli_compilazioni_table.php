<?php

// database/migrations/2025_08_10_000002_create_modelli_compilazioni_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('modelli_compilazioni', function (Blueprint $table) {
            $table->id();
            $table->foreignId('modello_id')->constrained('modelli_dinamici')->cascadeOnUpdate()->nullOnDelete();
            $table->string('target_type'); // es: App\Models\ManutenzioneRegistro
            $table->unsignedBigInteger('target_id');
            $table->unsignedInteger('version')->default(1);
            $table->json('payload_json');  // <-- tutto il form compilato
            $table->unsignedBigInteger('submitted_by')->nullable(); // opzionale
            $table->boolean('is_draft')->default(false);
            $table->timestamps();

            $table->index(['target_type','target_id']);
        });
    }
    public function down(): void {
        Schema::dropIfExists('modelli_compilazioni');
    }
};
