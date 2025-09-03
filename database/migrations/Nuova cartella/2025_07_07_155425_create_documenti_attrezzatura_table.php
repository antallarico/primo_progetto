<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('documenti_attrezzatura', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('attrezzatura_id');
            $table->string('tipo_documento');
            $table->string('file_path');
            $table->date('data_documento')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();

            $table->foreign('attrezzatura_id')->references('id')->on('attrezzature')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('documenti_attrezzatura');
    }
};
