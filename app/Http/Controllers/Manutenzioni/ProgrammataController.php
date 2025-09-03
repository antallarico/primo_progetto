<?php

namespace App\Http\Controllers\Manutenzioni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Attrezzatura;
use App\Models\ManutenzioneProgrammata;
use App\Models\ManutenzioneTipologia;
use App\Models\ManutenzioneCompetenza;

class ProgrammataController extends Controller
{
    public function index()
    {
        $manutenzioni = ManutenzioneProgrammata::with(['attrezzatura', 'tipologia', 'competenza'])->get();
        return view('manutenzioni.programmate.index', compact('manutenzioni'));
    }

    public function create()
    {
        $attrezzature = Attrezzatura::orderBy('nome')->get();
        $tipologie = ManutenzioneTipologia::orderBy('nome')->get();
        $competenze = ManutenzioneCompetenza::orderBy('nome')->get();

        return view('manutenzioni.programmate.create', compact('attrezzature', 'tipologie', 'competenze'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'attrezzatura_id' => 'required|exists:attrezzature,id',
            'tipologia_id' => 'required|exists:manutenzioni_tipologie,id',
            'competenza_id' => 'nullable|exists:manutenzioni_competenze,id',
            'frequenza_mesi' => 'nullable|integer|min:1',
            'scadenza_prossima' => 'nullable|date',
            'note' => 'nullable|string',
        ]);

        ManutenzioneProgrammata::create($validated);

        return redirect()->route('manutenzioni.programmate.index')->with('success', 'Manutenzione programmata creata con successo');
    }

    public function edit($id)
    {
        $manutenzione = ManutenzioneProgrammata::findOrFail($id);
        $attrezzature = Attrezzatura::orderBy('nome')->get();
        $tipologie = ManutenzioneTipologia::orderBy('nome')->get();
        $competenze = ManutenzioneCompetenza::orderBy('nome')->get();

        return view('manutenzioni.programmate.edit', compact('manutenzione', 'attrezzature', 'tipologie', 'competenze'));
    }

    public function update(Request $request, $id)
    {
        $manutenzione = ManutenzioneProgrammata::findOrFail($id);

        $validated = $request->validate([
            'attrezzatura_id' => 'required|exists:attrezzature,id',
            'tipologia_id' => 'required|exists:manutenzioni_tipologie,id',
            'competenza_id' => 'nullable|exists:manutenzioni_competenze,id',
            'frequenza_mesi' => 'nullable|integer|min:1',
            'scadenza_prossima' => 'nullable|date',
            'note' => 'nullable|string',
        ]);

        $manutenzione->update($validated);

        return redirect()->route('manutenzioni.programmate.index')->with('success', 'Manutenzione aggiornata con successo');
    }

    public function destroy($id)
    {
        $manutenzione = ManutenzioneProgrammata::findOrFail($id);
        $manutenzione->delete();

        return redirect()->route('manutenzioni.programmate.index')->with('success', 'Manutenzione eliminata');
    }

    public function show($id)
    {
        return redirect()->route('manutenzioni.programmate.edit', $id);
    }
}
