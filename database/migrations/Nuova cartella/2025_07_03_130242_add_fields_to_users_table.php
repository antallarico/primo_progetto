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
    Schema::table('users', function (Blueprint $table) {
        $table->string('nome')->after('id');
        $table->string('cognome')->after('nome');
        $table->string('ruolo')->nullable()->after('email'); // es. admin, medico, rspp...
        $table->string('telefono')->nullable()->after('ruolo');
        $table->boolean('attivo')->default(true)->after('telefono');
    });
}

public function down(): void
{
    Schema::table('users', function (Blueprint $table) {
        $table->dropColumn(['nome', 'cognome', 'ruolo', 'telefono', 'attivo']);
    });
}

};
