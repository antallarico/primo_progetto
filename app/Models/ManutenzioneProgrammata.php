<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManutenzioneProgrammata extends Model
{
    use HasFactory;

    protected $table = 'manutenzioni_programmate';

    protected $fillable = [
        'attrezzatura_id', 'tipologia_id', 'data_scadenza',
        'competenza_id', 'stato', 'note'
    ];

    public function tipologia()
    {
        return $this->belongsTo(ManutenzioneTipologia::class, 'tipologia_id');
    }

    public function competenza()
    {
        return $this->belongsTo(ManutenzioneCompetenza::class, 'competenza_id');
    }

    public function attrezzatura()
    {
        return $this->belongsTo(Attrezzatura::class, 'attrezzatura_id');
    }
}
