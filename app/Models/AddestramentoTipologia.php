<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AddestramentoTipologia extends Model
{
    protected $table = 'addestramenti_tipologie';

    protected $fillable = [
        'nome',
        'descrizione',
        'modello_id',
        'validita_mesi',
        'attiva',
    ];

    protected $casts = [
        'attiva' => 'boolean',
        'validita_mesi' => 'integer',
    ];

    public function modello()
    {
        return $this->belongsTo(ModelloDinamico::class, 'modello_id');
    }

    public function addestramenti()
    {
        return $this->hasMany(AddestramentoRegistro::class, 'tipologia_id');
    }
}
