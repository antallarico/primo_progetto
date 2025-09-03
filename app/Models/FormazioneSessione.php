<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class FormazioneSessione extends Model
{
    protected $table = 'formazione_sessioni';

    protected $fillable = [
        'corso_id',
        'data_sessione',
        'durata_effettiva',
        'soggetto_formatore',
        'docente',
        'note',
        'luogo'
    ];

    public function corso(): BelongsTo
    {
        return $this->belongsTo(FormazioneCorso::class, 'corso_id');
    }

    public function partecipazioni(): HasMany
    {
        return $this->hasMany(Formazione::class, 'sessione_id');
    }
}
