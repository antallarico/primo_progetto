<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProdottoChimico extends Model
{
    protected $table = 'prodotti_chimici';
    protected $fillable = [
        'nome_commerciale','tipo','codice_interno','codice_fornitore',
        'fornitore','ufi','stato','note'
    ];
    protected $casts = ['stato' => 'boolean'];

    public function sds() { return $this->hasMany(ProdottoChimicoSds::class, 'prodotto_id'); }
    public function sdsAttuale()
	{
		// Ultima per data_revisione (a parità di data, id più alto)
		return $this->hasOne(ProdottoChimicoSds::class, 'prodotto_id')
			->latest('data_revisione')
			->latest('id');
		// Se vuoi usare Laravel 9+ c'è anche: ->latestOfMany('data_revisione')
	}

}
