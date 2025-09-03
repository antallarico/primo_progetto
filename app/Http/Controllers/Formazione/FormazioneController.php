<?php

namespace App\Http\Controllers\Formazione;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Formazione;
use App\Models\FormazioneSessione;
use App\Models\Lavoratore;
use App\Helpers\FormazioneHelper;
use App\Models\FormazioneCorso;



class FormazioneController extends Controller
{
    // Elenco partecipanti per una sessione
    public function index($sessione_id)
    {
        $sessione = FormazioneSessione::with('corso')->findOrFail($sessione_id);
        $partecipazioni = Formazione::with('lavoratore')
            ->where('sessione_id', $sessione_id)
            ->get();

        return view('formazione.registro.index', compact('sessione', 'partecipazioni'));
    }

    // Form per aggiungere partecipazione
    public function create($sessione_id)
    {
        $sessione = FormazioneSessione::findOrFail($sessione_id);
        $lavoratori = Lavoratore::where('attivo', 1)->orderBy('cognome')->get();

        return view('formazione.registro.create', compact('sessione', 'lavoratori'));
    }

    // Salva partecipazione
    public function store(Request $request, $sessione_id)
    {
        $request->validate([
            'lavoratore_id' => 'required|exists:lavoratori,id',
            'data_formazione' => 'required|date',
            'data_scadenza' => 'nullable|date',
            'attestato' => 'required|in:presente,non_presente,non_previsto,in_attesa,verbale_interno,attestato_interno',
            'link_attestato' => 'nullable|url'
        ]);

        Formazione::create([
            'sessione_id' => $sessione_id,
            'lavoratore_id' => $request->lavoratore_id,
            'data_formazione' => $request->data_formazione,
            'data_scadenza' => $request->data_scadenza,
            'attestato' => $request->attestato,
            'link_attestato' => $request->link_attestato,
        ]);

        return redirect()->route('formazione.registro.index', $sessione_id)
            ->with('success', 'Partecipazione registrata con successo.');
    }

    // Modifica partecipazione
    public function edit($id)
    {
        $partecipazione = Formazione::with('sessione', 'lavoratore')->findOrFail($id);
        return view('formazione.registro.edit', ['formazione' => $partecipazione]);
    }

    // Salva modifica partecipazione
    public function update(Request $request, $id)
    {
        $request->validate([
            'data_formazione' => 'required|date',
            'data_scadenza' => 'nullable|date',
            'attestato' => 'required|in:presente,non_presente,non_previsto,in_attesa,verbale_interno,attestato_interno' ,
            'link_attestato' => 'nullable|url'
        ]);

        $partecipazione = Formazione::findOrFail($id);

        $partecipazione->update([
            'data_formazione' => $request->data_formazione,
            'data_scadenza' => $request->data_scadenza,
            'attestato' => $request->attestato,
            'link_attestato' => $request->link_attestato,
        ]);

        return redirect()->route('formazione.registro.index', $partecipazione->sessione_id)
            ->with('success', 'Partecipazione aggiornata.');
    }

    // Elimina partecipazione
    public function destroy($id)
    {
        $partecipazione = Formazione::findOrFail($id);
        $sessione_id = $partecipazione->sessione_id;
        $partecipazione->delete();

        return redirect()->route('formazione.registro.index', $sessione_id)
            ->with('success', 'Partecipazione eliminata.');
    }
	
	// Mostra lo storico formazione di un lavoratore
    public function storicolavoratore($lavoratore_id)
    {
        $lavoratore = Lavoratore::findOrFail($lavoratore_id);

        $formazioni = Formazione::with(['sessione.corso'])
            ->where('lavoratore_id', $lavoratore_id)
            ->orderByDesc('data_formazione')
            ->get();

        return view('formazione.storicolavoratore', compact('lavoratore', 'formazioni'));
    }

public function lavoratori()
{
    $lavoratori = Lavoratore::with(['formazioni.corso'])->get();

    // Tutte le competenze definite nei corsi
    $competenze = FormazioneCorso::whereNotNull('corso_competenza')
        ->distinct()
        ->pluck('corso_competenza');

    $riepilogo = [];

    foreach ($lavoratori as $lavoratore) {
        $riepilogo[$lavoratore->id]['nome'] = $lavoratore->nome . ' ' . $lavoratore->cognome;

        foreach ($competenze as $comp) {
            $riepilogo[$lavoratore->id]['competenze'][$comp] = FormazioneHelper::calcolaStatoFormazione($lavoratore->id, $comp);
        }
    }

    return view('formazione.storico.lavoratori', compact('riepilogo', 'competenze'));
}
	
public function storicocorso($corso_id)
{
     
	$corso = FormazioneCorso::findOrFail($corso_id);

    $sessioni = \App\Models\FormazioneSessione::with(['partecipazioni.lavoratore'])
        ->where('corso_id', $corso_id)
        ->orderByDesc('data_sessione')
        ->get();

    return view('formazione.storicocorso', compact('corso', 'sessioni'));
}


}