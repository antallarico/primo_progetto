<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManutenzioneComponenteSostituito extends Model
{
    use HasFactory;

    protected $table = 'manutenzioni_componenti_sostituiti';

    protected $fillable = [
        'registro_id', 'nome_componente', 'quantita', 'note'
    ];

    public function registro()
    {
        return $this->belongsTo(ManutenzioneRegistro::class, 'registro_id');
    }
}
