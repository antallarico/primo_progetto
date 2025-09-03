<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ManutenzioneRegistro extends Model
{
    use HasFactory;

    protected $table = 'manutenzioni_registro';

    protected $fillable = [
		'attrezzatura_id',
		//'data', campo previsto per uso futuro
		'data_esecuzione',
		'modello_id',
		'tipologia_id',
		'competenza_id',
		'compilazione_id',        
		'esito', 
		'documento_verifica', 
		'note'
		
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
	
	public function programmata() 
	{
		return $this->belongsTo(\App\Models\ManutenzioneProgrammata::class);
	}	
	// serve???
	public function registro()
	{
//		return $this->belongsTo(\App\Models\ManutenzioneRegistro::class, 'registro_id');
	}
	
	public function compilazione()
    {
        return $this->belongsTo(ModelloCompilazione::class, 'compilazione_id');
    }

    public function componentiSostituiti()
    {
        return $this->hasMany(ManutenzioneComponenteSostituito::class, 'registro_id');
    }
	
	public function modello()
	{
		return $this->belongsTo(\App\Models\ModelloDinamico::class, 'modello_id');
	}
	
}
