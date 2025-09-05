<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DpiConsegna extends Model
{
    protected $table = 'dpi_consegne';
    protected $fillable = [
        'lavoratore_id','articolo_id','quantita','data_consegna','data_primo_utilizzo',
        'data_scadenza','stato','motivo_chiusura','note'
    ];
    protected $casts = [
        'data_consegna' => 'date',
        'data_primo_utilizzo' => 'date',
        'data_scadenza' => 'date',
    ];

    public function lavoratore(){ return $this->belongsTo(\App\Models\Lavoratore::class, 'lavoratore_id'); } // cambia se è Dipendente
    public function articolo(){ return $this->belongsTo(DpiArticolo::class, 'articolo_id'); }
    public function allegati(){ return $this->hasMany(DpiConsegnaAllegato::class, 'consegna_id'); }
}
