<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lavoratore extends Model
{
    protected $table = 'lavoratori';

    protected $fillable = [
        'nome',
        'cognome',
        'codice_fiscale',
        'telefono',
        'email',
        'data_nascita',
        'data_assunzione',
        'attivo',
    ];

    public $timestamps = true;


	public function formazioni()
	{
		return $this->hasMany(\App\Models\Formazione::class, 'lavoratore_id');
	}

}