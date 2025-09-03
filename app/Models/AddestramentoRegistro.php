<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddestramentoRegistro extends Model
{
    protected $table = 'addestramenti_registro';

    protected $fillable = [
        'lavoratore_id',
        'tipologia_id',
        'attrezzatura_id',
		'ambito',
        'modello_id',
        'payload_json',
        'data_addestramento',
        'esito',
        'istruttore_id',
        'istruttore_nome',
        'scade_il',
        'note',		
    ];

    protected $casts = [
        'payload_json' => 'array',
        'data_addestramento' => 'date',
        'scade_il' => 'date',
    ];

    public function lavoratore()
    {
        return $this->belongsTo(\App\Models\Lavoratore::class, 'lavoratore_id');
    }

    public function istruttore()
    {
        return $this->belongsTo(\App\Models\Lavoratore::class, 'istruttore_id');
    }

    public function tipologia()
    {
        return $this->belongsTo(AddestramentoTipologia::class, 'tipologia_id');
    }

    public function attrezzatura()
    {
        return $this->belongsTo(Attrezzatura::class, 'attrezzatura_id');
    }

    public function modello()
    {
        return $this->belongsTo(ModelloDinamico::class, 'modello_id');
    }
	
	// AddestramentoRegistro
	public function attrezzature()
	{
		return $this->belongsToMany(
			\App\Models\Attrezzatura::class,
			'addestramenti_registro_attrezzature',
			'addestramento_id',
			'attrezzatura_id'
		);
	}


}
