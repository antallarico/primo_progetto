<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManutenzioneCompetenza extends Model
{
    use HasFactory;

    protected $table = 'manutenzioni_competenze';

    protected $fillable = [
        'nome', 'tipo', 'contatti', 'abilitazioni', 'note'
    ];

    public function programmate()
    {
        return $this->hasMany(ManutenzioneProgrammata::class, 'competenza_id');
    }

    public function registro()
    {
        return $this->hasMany(ManutenzioneRegistro::class, 'competenza_id');
    }
}
