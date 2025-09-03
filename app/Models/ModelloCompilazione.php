<?php

// app/Models/ModelloCompilazione.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ModelloCompilazione extends Model
{
    protected $table = 'modelli_compilazioni';

    protected $fillable = [
        'modello_id',
        'target_type',
        'target_id',
        'version',
        'payload_json',
        'submitted_by',
        'is_draft',
    ];

    protected $casts = [
        'payload_json' => 'array',
        'is_draft'     => 'boolean',
		'version'      => 'integer',
    ];

    public function modello() {
        return $this->belongsTo(ModelloDinamico::class, 'modello_id');
    }

    // Se un giorno vuoi morph reali:
    public function target(): MorphTo {
        return $this->morphTo(__FUNCTION__, 'target_type', 'target_id');
    }
}
