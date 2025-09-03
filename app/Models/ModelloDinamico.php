<?php

// app/Models/ModelloDinamico.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ModelloDinamico extends Model
{
    protected $table = 'modelli_dinamici';

    protected $fillable = [
        'nome',
        'modulo',
        'tipologia_id',
        //'contenuto',     // legacy (resta)
        'schema_json',   // nuovo
        'layout_json',   // nuovo
        'version',       // nuovo
        'stato',         // nuovo
    ];

    protected $casts = [
        //'contenuto'   => 'array',
        'schema_json' => 'array',
        'layout_json' => 'array',
    ];

    public function compilazioni() {
        return $this->hasMany(ModelloCompilazione::class, 'modello_id');
    }
}
