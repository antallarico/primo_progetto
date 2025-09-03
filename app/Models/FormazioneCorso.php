<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormazioneCorso extends Model
{
    protected $table = 'formazione_corsi';

    protected $fillable = [
        'codice',
        'titolo',
        'descrizione',
        'durata_ore',
        'validita_mesi',
        'normato',
        'obbligatorio',
        'corso_competenza',
        'tipo_corso',
        'aggiornamento_richiesto',
        'soglia_ore_rolling',
        'rolling_finestra_anni',
    ];

    public function sessioni(): HasMany
    {
        return $this->hasMany(FormazioneSessione::class, 'corso_id');
    }
}
