<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('lavoratori', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('cognome');
            $table->string('codice_fiscale')->unique();
            $table->string('telefono')->nullable();
            $table->string('email')->nullable();
            $table->boolean('attivo')->default(true);
            $table->date('data_assunzione')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('lavoratori');
    }
};


