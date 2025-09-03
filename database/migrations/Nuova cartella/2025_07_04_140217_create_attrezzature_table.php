<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attrezzature', function (Blueprint $table) {
            $table->id();
            $table->string('nome');
            $table->string('marca')->nullable();
            $table->string('modello')->nullable();
            $table->string('matricola')->nullable()->unique();
            $table->date('data_fabbricazione')->nullable();
            $table->string('ubicazione')->nullable();
            $table->enum('stato', ['in uso', 'fuori uso', 'dismessa'])->default('in uso');
            $table->string('tipo')->nullable(); // impianto, attrezzatura, macchinario, ecc.
            $table->unsignedBigInteger('attrezzatura_padre_id')->nullable();
            $table->foreign('attrezzatura_padre_id')->references('id')->on('attrezzature')->onDelete('set null');
            $table->text('note')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attrezzature');
    }
};
