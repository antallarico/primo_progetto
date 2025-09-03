<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AttrezzaturaScheda extends Model
{
    protected $table = 'attrezzature_schede';

    protected $fillable = [
        'attrezzatura_id',
        'tipologia_id',
        'dati',
		'nome',
		'contenuto',
    ];

    protected $casts = [
        'dati' => 'array',
		'contenuto' => 'array',
    ];

    public function attrezzatura()
    {
        return $this->belongsTo(Attrezzatura::class);
    }

    public function tipologia()
    {
        return $this->belongsTo(AttrezzaturaTipologia::class);
    }
}
