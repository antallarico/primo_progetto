<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DpiDotazioneRichiesta extends Model
{
    protected $table = 'dpi_dotazioni_richieste';
    protected $fillable = ['lavoratore_id','tipo_id','quantita_richiesta','fonte'];

    public function tipo(){ return $this->belongsTo(DpiTipo::class, 'tipo_id'); }
    public function lavoratore(){ return $this->belongsTo(\App\Models\Lavoratore::class, 'lavoratore_id'); } // cambia se è Dipendente
}
