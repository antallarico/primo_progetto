<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdottoChimicoSds extends Model
{
    protected $table = 'prodotti_chimici_sds';
    protected $fillable = [
        'prodotto_id','data_revisione','rev_num','lingua','file_path',
        'attuale','prossima_review'
    ];
	protected $casts = [
		'data_revisione' => 'date',
		'created_at'     => 'datetime',
		'updated_at'     => 'datetime',
	];

   
    public function prodotto(){ return $this->belongsTo(ProdottoChimico::class, 'prodotto_id'); }
    public function clp(){ return $this->hasOne(ProdottoChimicoSdsClp::class, 'sds_id'); }
}
