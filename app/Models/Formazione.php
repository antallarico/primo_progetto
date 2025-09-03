<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Formazione extends Model
{
    protected $table = 'formazione';

    protected $fillable = [
        'lavoratore_id',
        'sessione_id',
        'data_formazione',
        'data_scadenza',
        'attestato',
        'link_attestato'
    ];

    public function lavoratore(): BelongsTo
    {
        return $this->belongsTo(\App\Models\Lavoratore::class, 'lavoratore_id');
    }

    public function sessione(): BelongsTo
    {
        return $this->belongsTo(FormazioneSessione::class, 'sessione_id');
    }
	
	public function getRouteKeyName()
    {
        return 'id';
    }

	public function corso()
	{
		return $this->hasOneThrough(
			\App\Models\FormazioneCorso::class,
			\App\Models\FormazioneSessione::class,
			'id',             // Foreign key on FormazioneSessione
			'id',             // Foreign key on FormazioneCorso
			'sessione_id',    // Local key on Formazione
			'corso_id'        // Local key on FormazioneSessione
		);
	}


}