<?php

namespace App\Http\Controllers\Manutenzioni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManutenzioneCompetenza;

class CompetenzaController extends Controller
{
    public function index()
    {
        $competenze = ManutenzioneCompetenza::all();
        return view('manutenzioni.competenze.index', compact('competenze'));
    }

    public function create()
    {
        return view('manutenzioni.competenze.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:interno,esterno',
            'contatti' => 'nullable|string|max:255',
            'abilitazioni' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        ManutenzioneCompetenza::create($validated);

        return redirect()->route('manutenzioni.competenze.index')->with('success', 'Competenza creata con successo');
    }

    public function edit($id)
    {
        $competenza = ManutenzioneCompetenza::findOrFail($id);
        return view('manutenzioni.competenze.edit', compact('competenza'));
    }

    public function update(Request $request, $id)
    {
        $competenza = ManutenzioneCompetenza::findOrFail($id);

        $validated = $request->validate([
            'nome' => 'required|string|max:255',
            'tipo' => 'required|in:interno,esterno',
            'contatti' => 'nullable|string|max:255',
            'abilitazioni' => 'nullable|string',
            'note' => 'nullable|string',
        ]);

        $competenza->update($validated);

        return redirect()->route('manutenzioni.competenze.index')->with('success', 'Competenza aggiornata con successo');
    }

    public function destroy($id)
    {
        $competenza = ManutenzioneCompetenza::findOrFail($id);
        $competenza->delete();

        return redirect()->route('manutenzioni.competenze.index')->with('success', 'Competenza eliminata con successo');
    }

    public function show($id)
    {
        return redirect()->route('manutenzioni.competenze.edit', $id);
    }
}
