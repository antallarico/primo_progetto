<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DpiConsegnaAllegato extends Model
{
    protected $table = 'dpi_consegna_allegati';
    protected $fillable = ['consegna_id','nome_file','path'];

    public function consegna(){ return $this->belongsTo(DpiConsegna::class, 'consegna_id'); }
}
