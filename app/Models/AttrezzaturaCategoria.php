<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Models\Attrezzatura;

class AttrezzaturaCategoria extends Model
{
    protected $table = 'attrezzature_categorie'; // tabella aggiornata

    protected $fillable = ['nome'];

    /**
     * Relazione con le attrezzature appartenenti a questa categoria.
     */
    public function attrezzature(): HasMany
    {
        return $this->hasMany(Attrezzatura::class, 'categoria_id');
    }
}
