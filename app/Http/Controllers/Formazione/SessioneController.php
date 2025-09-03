<?php

namespace App\Http\Controllers\Formazione;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\FormazioneSessione;
use App\Models\FormazioneCorso;

class SessioneController extends Controller
{
    public function index()
	{
		$sessioni = FormazioneSessione::with('corso')
			->withCount('partecipazioni') // <--- aggiungi questo
			->orderByDesc('data_sessione')
			->get();

		return view('formazione.sessioni.index', compact('sessioni'));
	}

    public function create()
    {
        $corsi = FormazioneCorso::orderBy('titolo')->get();
        return view('formazione.sessioni.create', compact('corsi'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'corso_id' => 'required|exists:formazione_corsi,id',
            'data_sessione' => 'required|date',
            'durata_effettiva' => 'nullable|numeric',
            'soggetto_formatore' => 'nullable|string|max:255',
            'docente' => 'nullable|string|max:255',
            'luogo' => 'nullable|string|max:255',
            'note' => 'nullable|string'
        ]);

        FormazioneSessione::create($data);
        return redirect()->route('formazione.sessioni.index')->with('success', 'Sessione creata con successo.');
    }

    public function edit(FormazioneSessione $sessione)
    {
        $corsi = FormazioneCorso::orderBy('titolo')->get();
        return view('formazione.sessioni.edit', compact('sessione', 'corsi'));
    }

    public function update(Request $request, FormazioneSessione $sessione)
    {
        $data = $request->validate([
            'corso_id' => 'required|exists:formazione_corsi,id',
            'data_sessione' => 'required|date',
            'durata_effettiva' => 'nullable|numeric',
            'soggetto_formatore' => 'nullable|string|max:255',
            'docente' => 'nullable|string|max:255',
            'luogo' => 'nullable|string|max:255',
            'note' => 'nullable|string'
        ]);

        $sessione->update($data);
        return redirect()->route('formazione.sessioni.index')->with('success', 'Sessione aggiornata.');
    }

    public function destroy(FormazioneSessione $sessione)
    {
        $sessione->delete();
        return redirect()->route('formazione.sessioni.index')->with('success', 'Sessione eliminata.');
    }
}
