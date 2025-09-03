<?php

// database/migrations/2025_08_10_000001_add_schema_layout_to_modelli_dinamici.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::table('modelli_dinamici', function (Blueprint $table) {
            $table->json('schema_json')->nullable()->after('contenuto');
            $table->json('layout_json')->nullable()->after('schema_json');
            $table->unsignedInteger('version')->default(1)->after('layout_json');
            $table->string('stato', 20)->default('pubblicato')->after('version'); // o 'bozza'
        });
    }
    public function down(): void {
        Schema::table('modelli_dinamici', function (Blueprint $table) {
            $table->dropColumn(['schema_json','layout_json','version','stato']);
        });
    }
};
