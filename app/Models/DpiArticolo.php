<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DpiArticolo extends Model
{
    protected $table = 'dpi_articoli';
    protected $fillable = [
        'tipo_id','marca','modello','taglia','codice_fornitore','ean_sku',
        'quantita_disponibile','validita_mesi_default','note','attivo'
    ];
    protected $casts = [
        'attivo' => 'boolean',
        'quantita_disponibile' => 'integer',
        'validita_mesi_default' => 'integer',
    ];

    public function tipo()
    {
        return $this->belongsTo(DpiTipo::class, 'tipo_id');
    }

    public function consegne()
    {
        return $this->hasMany(DpiConsegna::class, 'articolo_id');
    }
}
