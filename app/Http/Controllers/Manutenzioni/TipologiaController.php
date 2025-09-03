<?php

namespace App\Http\Controllers\Manutenzioni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManutenzioneTipologia;
use App\Models\ModelloDinamico;

class TipologiaController extends Controller
{
    public function index()
    {
        $tipologie = ManutenzioneTipologia::withCount('modelliDinamici')
            ->with('modelliDinamici') // se vuoi elencarli in view
            ->orderBy('nome')
            ->get();

        return view('manutenzioni.tipologie.index', compact('tipologie'));
    }

    public function create()
    {
        return view('manutenzioni.tipologie.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome'              => 'required|string|max:255',
            'descrizione'       => 'nullable|string',
            'periodicita_mesi'  => 'nullable|integer|min:1',
            'obbligatoria'      => 'sometimes|boolean',
            'documentabile'     => 'sometimes|boolean',
            'note'              => 'nullable|string',
        ]);

        // normalizza booleani anche se non spuntati
        $validated['obbligatoria']  = (bool)$request->input('obbligatoria', false);
        $validated['documentabile'] = (bool)$request->input('documentabile', false);

        ManutenzioneTipologia::create($validated);

        return redirect()->route('manutenzioni.tipologie.index')
            ->with('success', 'Tipologia creata con successo');
    }

    public function edit($id)
    {
        // niente più checklistDinamica
        $tipologia = ManutenzioneTipologia::with('modelliDinamici')->findOrFail($id);
        return view('manutenzioni.tipologie.edit', compact('tipologia'));
    }

    public function update(Request $request, $id)
    {
        $tipologia = ManutenzioneTipologia::findOrFail($id);

        $validated = $request->validate([
            'nome'              => 'required|string|max:255',
            'descrizione'       => 'nullable|string',
            'periodicita_mesi'  => 'nullable|integer|min:1',
            'obbligatoria'      => 'sometimes|boolean',
            'documentabile'     => 'sometimes|boolean',
            'note'              => 'nullable|string',
        ]);

        $validated['obbligatoria']  = (bool)$request->input('obbligatoria', false);
        $validated['documentabile'] = (bool)$request->input('documentabile', false);

        $tipologia->update($validated);

        return redirect()->route('manutenzioni.tipologie.index')
            ->with('success', 'Tipologia aggiornata con successo');
    }

    public function destroy($id)
    {
        $tipologia = ManutenzioneTipologia::findOrFail($id);

        // i ModelliDinamici hanno tipologia_id nullable → scollega prima di cancellare la tipologia
        ModelloDinamico::where('tipologia_id', $tipologia->id)->update(['tipologia_id' => null]);

        $tipologia->delete();

        return redirect()->route('manutenzioni.tipologie.index')
            ->with('success', 'Tipologia eliminata con successo');
    }

    public function show($id)
    {
        return redirect()->route('manutenzioni.tipologie.edit', $id);
    }
}

