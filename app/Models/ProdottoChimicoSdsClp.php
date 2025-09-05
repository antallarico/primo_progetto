<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdottoChimicoSdsClp extends Model
{
    protected $table = 'prodotti_chimici_sds_clp';
    protected $fillable = ['sds_id','signal_word','pittogrammi','frasi_h','frasi_p','categorie_pericolo'];
    protected $casts = [
        'pittogrammi' => 'array',
        'frasi_h' => 'array',
        'frasi_p' => 'array',
        'categorie_pericolo' => 'array',
    ];

    public function sds(){ return $this->belongsTo(ProdottoChimicoSds::class, 'sds_id'); }
}
