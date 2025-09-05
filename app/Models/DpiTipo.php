	<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DpiTipo extends Model
{
    protected $table = 'dpi_tipi';
    protected $fillable = [
        'nome','categoria','norma_en','rischi_coperti',
        'politica_scadenza_default','note','attivo'
    ];
    protected $casts = [
        'attivo' => 'boolean',
        'politica_scadenza_default' => 'array',
    ];

    public function articoli()
    {
        return $this->hasMany(DpiArticolo::class, 'tipo_id');
    }
}
