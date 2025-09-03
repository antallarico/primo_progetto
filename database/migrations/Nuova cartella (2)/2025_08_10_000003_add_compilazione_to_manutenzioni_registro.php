<?php

// database/migrations/2025_08_10_000003_add_compilazione_to_manutenzioni_registro.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('manutenzioni_registro', function (Blueprint $table) {
            $table->foreignId('compilazione_id')
                ->nullable()
                ->after('modello_id')
                ->constrained('modelli_compilazioni')
                ->nullOnDelete()
                ->cascadeOnUpdate();
        });
    }
    public function down(): void {
        Schema::table('manutenzioni_registro', function (Blueprint $table) {
            $table->dropConstrainedForeignId('compilazione_id');
        });
    }
};
