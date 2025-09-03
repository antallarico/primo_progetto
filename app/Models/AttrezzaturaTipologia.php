<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AttrezzaturaTipologia extends Model
{
    protected $table = 'attrezzature_tipologie';

    // Aggiunta dei campi fondamentali per la gestione delle schede
    protected $fillable = [
        'nome',
        'has_scheda',
        'scheda_tabella',
        'scheda_view',
    ];

    public function attrezzature(): HasMany
    {
        return $this->hasMany(Attrezzatura::class, 'tipologia_id');
    }
	
	public function getRouteKeyName()
	{
		return 'id';
	}

}
