<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\AttrezzaturaCategoria;
use App\Models\AttrezzaturaTipologia;
use App\Models\ModelloCompilazione;

class Attrezzatura extends Model
{
    use HasFactory;

    protected $table = 'attrezzature';

    protected $fillable = [
        'nome',
        'marca',
        'modello',
        'matricola',
		'matricola_azienda',
        'data_fabbricazione',
        'ubicazione',
        'stato',
		'dich_ce',        
        'tipo',
        'attrezzatura_padre_id',
		'note',
        'categoria_id',        
        'tipologia_id',
        'modello_id',		
    ];

    public function categoria()
    {
        return $this->belongsTo(AttrezzaturaCategoria::class, 'categoria_id');
    }

    public function attrezzaturaPadre()
    {
        return $this->belongsTo(Attrezzatura::class, 'attrezzatura_padre_id');
    }

    public function tipologia()
    {
        return $this->belongsTo(AttrezzaturaTipologia::class, 'tipologia_id');
    }

    // Compilazioni dinamiche (morph al repository unico)
    public function compilazioni()
    {
        return $this->morphMany(ModelloCompilazione::class, 'target', 'target_type', 'target_id');
    }

    // Solo compilazioni relative a modelli del modulo "Attrezzature"
    public function compilazioniScheda()
    {
        return $this->compilazioni()->whereHas('modello', function ($q) {
            $q->where('modulo', 'Attrezzature');
        });
    }

    public function ultimaSchedaCompilata()
    {
        return $this->compilazioniScheda()->latest('id')->first();
    }
}
