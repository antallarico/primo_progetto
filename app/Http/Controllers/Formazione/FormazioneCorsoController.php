<?php

namespace App\Http\Controllers\Formazione;

use App\Http\Controllers\Controller;
use App\Models\FormazioneCorso;
use Illuminate\Http\Request;

class FormazioneCorsoController extends Controller
{
    public function index()
    {
        $corsi = FormazioneCorso::orderBy('titolo')->get();
        return view('formazione.corsi.index', compact('corsi'));
    }

    public function create()
    {
        return view('formazione.corsi.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'codice' => 'nullable|string|max:50',
            'titolo' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'durata_ore' => 'nullable|integer',
            'validita_mesi' => 'nullable|integer',
			'normato' => 'boolean',
            'obbligatorio' => 'boolean',
        ]);

        FormazioneCorso::create($data);
        return redirect()->route('formazione.corsi.index')->with('success', 'Corso creato con successo.');
    }

    public function edit(FormazioneCorso $corso)
    {
        return view('formazione.corsi.edit', compact('corso'));
    }

    public function update(Request $request, FormazioneCorso $corso)
    { 
	
        $data = $request->validate([
            'codice' => 'nullable|string|max:50',
            'titolo' => 'required|string|max:255',
            'descrizione' => 'nullable|string',
            'durata_ore' => 'nullable||numeric',
            'validita_mesi' => 'nullable|integer',
            'normato' => 'required|in:0,1',
            'obbligatorio' => 'required|in:0,1',
        ]);
/*		
		dd([
        'request_all' => $request->all(),
        'validated_data' => $data,
        'normato_type' => gettype($data['normato'] ?? null),
        'obbligatorio_type' => gettype($data['obbligatorio'] ?? null),
		]);
*/		
		$data['normato'] = (int) $data['normato'];
		$data['obbligatorio'] = (int) $data['obbligatorio'];

		$corso->update($data);
		return redirect()->route('formazione.corsi.index')->with('success', 'Corso aggiornato.');
    }


    public function destroy(FormazioneCorso $corso)
    {
        $corso->delete();
        return redirect()->route('formazione.corsi.index')->with('success', 'Corso eliminato.');
    }
}
