<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdottoChimicoUbicazione extends Model
{
    protected $table = 'prodotti_chimici_ubicazioni';
    protected $fillable = [
        'prodotto_id','ubicazione','quantita_disponibile','unita','note'
    ];
    protected $casts = [
        'quantita_disponibile' => 'float',
    ];

    public function prodotto(){ return $this->belongsTo(ProdottoChimico::class, 'prodotto_id'); }
}
