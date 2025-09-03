<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
	{
    Schema::create('utenti', function (Blueprint $table) {
        $table->id();
        $table->string('nome');
        $table->string('cognome');
        $table->string('email')->unique();
        $table->string('password');
        $table->string('ruolo')->default('utente'); // es. admin, medico, rspp, ecc.
        $table->boolean('attivo')->default(true);
        $table->rememberToken();
        $table->timestamps();
		});
	}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('utenti');
    }
};
