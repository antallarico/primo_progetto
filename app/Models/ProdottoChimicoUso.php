<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdottoChimicoUso extends Model
{
    protected $table = 'prodotti_chimici_usi';
    protected $fillable = [
        'prodotto_id','area','attrezzatura_id','processo','consumo_medio','unita','note'
    ];
    protected $casts = [
        'consumo_medio' => 'float',
    ];

    public function prodotto(){ return $this->belongsTo(ProdottoChimico::class, 'prodotto_id'); }
    public function attrezzatura(){ return $this->belongsTo(\App\Models\Attrezzatura::class, 'attrezzatura_id'); }
}

