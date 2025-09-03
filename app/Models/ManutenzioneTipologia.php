<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManutenzioneTipologia extends Model
{
    use HasFactory;

    protected $table = 'manutenzioni_tipologie';

    protected $fillable = [
        'nome', 'descrizione', 'periodicita_mesi',
        'obbligatoria', 'con_checklist', 'documentabile', 'note'
    ];

    public function programmate()
    {
        return $this->hasMany(ManutenzioneProgrammata::class, 'tipologia_id');
    }

    public function registro()
    {
        return $this->hasMany(ManutenzioneRegistro::class, 'tipologia_id');
    }
	
	public function modelliDinamici()
	{
		return $this->hasMany(\App\Models\ModelloDinamico::class, 'tipologia_id', 'id')
					->where('modulo', 'Manutenzioni');
	}

}
