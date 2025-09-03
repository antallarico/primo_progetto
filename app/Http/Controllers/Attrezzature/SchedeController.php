<?php

namespace App\Http\Controllers\Attrezzature;

use App\Http\Controllers\Controller;
use App\Models\Attrezzatura;
use App\Models\ModelloDinamico;
use App\Models\ModelloCompilazione;
use App\Support\DynFormValidator;
use Illuminate\Http\Request;

class SchedeController extends Controller
{
    // Se esiste già una compilazione → edit, altrimenti create
    public function show(Attrezzatura $attrezzatura)
    {
        $comp = ModelloCompilazione::where('target_type', Attrezzatura::class)
            ->where('target_id', $attrezzatura->id)
            ->latest('id')
            ->first();

        return $comp
            ? redirect()->route('attrezzature.schede.edit', $attrezzatura)
            : redirect()->route('attrezzature.schede.create', $attrezzatura);
    }

    public function create(Attrezzatura $attrezzatura)
    {
        $modelli = ModelloDinamico::where('modulo', 'Attrezzature')
            ->when($attrezzatura->tipologia_id, fn($q) => $q->where('tipologia_id', $attrezzatura->tipologia_id))
            ->orderBy('nome')
            ->get();

        // nessuna compilazione esistente in create
        $modello = null;
        $compilazione = null;

        return view('attrezzature.schede.create', compact('attrezzatura', 'modelli', 'modello', 'compilazione'));
    }

    public function store(Request $request, Attrezzatura $attrezzatura)
    {
        $data = $request->validate([
            'modello_id' => 'required|exists:modelli_dinamici,id',
            'payload'    => 'nullable|array',
        ]);

        $modello  = ModelloDinamico::findOrFail($data['modello_id']);
        $payload  = $data['payload'] ?? [];

        // valida/normalizza con helper condiviso
        [$clean, $errors] = DynFormValidator::validate($modello, $payload);
        if ($errors) return back()->withErrors($errors)->withInput();

        ModelloCompilazione::create([
            'modello_id'   => $modello->id,
            'target_type'  => Attrezzatura::class,
            'target_id'    => $attrezzatura->id,
            'version'      => $modello->version ?? 1,
            'payload_json' => $clean,
            'submitted_by' => null,
            'is_draft'     => false,
        ]);

        return redirect()->route('attrezzature.schede.edit', $attrezzatura)
            ->with('success', 'Scheda salvata.');
    }

    public function edit(Attrezzatura $attrezzatura)
    {
        $modelli = ModelloDinamico::where('modulo', 'Attrezzature')
            ->when($attrezzatura->tipologia_id, fn($q) => $q->where('tipologia_id', $attrezzatura->tipologia_id))
            ->orderBy('nome')
            ->get();

        // prendi l’ultima compilazione (se presente)
        $compilazione = ModelloCompilazione::where('target_type', Attrezzatura::class)
            ->where('target_id', $attrezzatura->id)
            ->latest('id')
            ->first();

        $modello = $compilazione ? $compilazione->modello : null;

        return view('attrezzature.schede.edit', compact('attrezzatura', 'modelli', 'modello', 'compilazione'));
    }

    public function update(Request $request, Attrezzatura $attrezzatura)
    {
        $data = $request->validate([
            'modello_id' => 'required|exists:modelli_dinamici,id',
            'payload'    => 'nullable|array',
        ]);

        $modello  = ModelloDinamico::findOrFail($data['modello_id']);
        $payload  = $data['payload'] ?? [];

        [$clean, $errors] = DynFormValidator::validate($modello, $payload);
        if ($errors) return back()->withErrors($errors)->withInput();

        // recupera (o crea) l’ultima compilazione come “corrente”
        $compilazione = ModelloCompilazione::where('target_type', Attrezzatura::class)
            ->where('target_id', $attrezzatura->id)
            ->latest('id')
            ->first();

        if ($compilazione) {
            $compilazione->update([
                'modello_id'   => $modello->id,
                'version'      => $modello->version ?? 1,
                'payload_json' => $clean,
                'is_draft'     => false,
            ]);
        } else {
            $compilazione = ModelloCompilazione::create([
                'modello_id'   => $modello->id,
                'target_type'  => Attrezzatura::class,
                'target_id'    => $attrezzatura->id,
                'version'      => $modello->version ?? 1,
                'payload_json' => $clean,
                'is_draft'     => false,
            ]);
        }

        return back()->with('success', 'Scheda aggiornata.');
    }
}
