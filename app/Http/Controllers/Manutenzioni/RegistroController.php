<?php

namespace App\Http\Controllers\Manutenzioni;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ManutenzioneRegistro;
//use App\Models\ManutenzioneRegistroVoce; dismesso
use App\Models\ManutenzioneProgrammata;
use App\Models\ManutenzioneTipologia;
use App\Models\ManutenzioneCompetenza;
use App\Models\Attrezzatura;
use App\Models\ModelloDinamico;
use App\Models\ModelloCompilazione;
use App\Support\DynFormValidator;


class RegistroController extends Controller
{
    public function index()
    {
        $interventi = ManutenzioneRegistro::with(['attrezzatura', 'tipologia', 'competenza', 'programmata'])->orderBy('data_esecuzione', 'desc')->get();
        return view('manutenzioni.registro.index', compact('interventi'));
    }

    public function create()
    {
        $attrezzature = Attrezzatura::orderBy('nome')->get();
        $tipologie = ManutenzioneTipologia::orderBy('nome')->get();
        $competenze = ManutenzioneCompetenza::orderBy('nome')->get();
        $programmate = ManutenzioneProgrammata::orderBy('id')->get();
		$modelli = ModelloDinamico::where('modulo', 'Manutenzioni')->get();
		$modello = \App\Models\ModelloDinamico::find(old('modello_id')); // o null
		$compilazione = null;

		return view('manutenzioni.registro.create', compact('attrezzature', 'tipologie', 'competenze', 'programmate', 'modelli','modello','compilazione'));

    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'attrezzatura_id' => 'required|exists:attrezzature,id',
            'tipologia_id' => 'nullable|exists:manutenzioni_tipologie,id',
			'modello_id' => 'nullable|exists:modelli_dinamici,id',
            'competenza_id' => 'nullable|exists:manutenzioni_competenze,id',
            'programmata_id' => 'nullable|exists:manutenzioni_programmate,id',
            'data_esecuzione' => 'required|date',
			//'data' => 'required|date',   --- campo previsto per uso futuro
            'esito' => 'nullable|string|max:255',
            'note' => 'nullable|string',
			'payload' => 'nullable|array', // <— nuovo: tutto il form dinamico
        ]);
		
		// payload nuovo form dinamico
		$payload = $validated['payload'] ?? null;
		unset($validated['payload']);
		
		$modello_id = $request->input('modello_id');
		$registro = ManutenzioneRegistro::create($validated);
		
		if ($modello_id && is_array($payload)) {
			$modello = ModelloDinamico::find($modello_id);
			if (!$modello) {
				return back()->withErrors(['modello_id' => 'Modello inesistente'])->withInput();
			}

			// ✅ valida & normalizza
			[$clean, $err] = DynFormValidator::validate($modello, $payload);
			if ($err) return back()->withErrors($err)->withInput();			
			
			$compilazione = ModelloCompilazione::create([
				'modello_id'   => $modello->id,
				'target_type'  => ManutenzioneRegistro::class,
				'target_id'    => $registro->id,
				'version'      => $modello->version ?? 1,
				'payload_json' => $clean,
				'submitted_by' => null,
				'is_draft'     => false,
			]);
			$registro->update(['compilazione_id' => $compilazione->id]);
		}

        return redirect()->route('manutenzioni.registro.index')->with('success', 'Intervento registrato con successo');
    }
	
    public function edit($id)
    {
		$intervento = ManutenzioneRegistro::findOrFail($id);
        $attrezzature = Attrezzatura::orderBy('nome')->get();
        $tipologie = ManutenzioneTipologia::orderBy('nome')->get();
        $competenze = ManutenzioneCompetenza::orderBy('nome')->get();
        $programmate = ManutenzioneProgrammata::orderBy('id')->get();
		$modelli = ModelloDinamico::where('modulo', 'Manutenzioni')->get();
		
		$modello = $intervento->modello_id ? ModelloDinamico::find($intervento->modello_id) : null;
		
		// 1) prova via FK se presente
		$compilazione = $intervento->compilazione_id ? ModelloCompilazione::find($intervento->compilazione_id) : null;
		// 2) fallback: cerca per target_type/target_id (ultima compilazione) (fallback per sicurezza)
		if (!$compilazione) {
			$compilazione = ModelloCompilazione::where('target_type', ManutenzioneRegistro::class)
				->where('target_id', $intervento->id)
				->latest('id')
				->first();
		}

		return view('manutenzioni.registro.edit', compact('intervento','attrezzature','tipologie','competenze','programmate','modelli','modello','compilazione'));		
    }

    public function update(Request $request, $id)
    {
        $intervento = ManutenzioneRegistro::findOrFail($id);

        $validated = $request->validate([
            'attrezzatura_id' => 'required|exists:attrezzature,id',
            'tipologia_id' => 'nullable|exists:manutenzioni_tipologie,id',
			'modello_id' => 'nullable|exists:modelli_dinamici,id',
            'competenza_id' => 'nullable|exists:manutenzioni_competenze,id',
            'programmata_id' => 'nullable|exists:manutenzioni_programmate,id',
            'data_esecuzione' => 'required|date',
            'esito' => 'nullable|string|max:100',
            'note' => 'nullable|string',
			'payload' => 'nullable|array', // <— nuovo: tutto il form dinamico
        ]);
		
		// payload nuovo form dinamico
		$payload = $validated['payload'] ?? null;
		unset($validated['payload']);
		
		$intervento->update($validated);
		
		if ($request->filled('modello_id') && is_array($payload)) {
			$modello = ModelloDinamico::find($request->modello_id);
			if (!$modello) {
				return back()->withErrors(['modello_id' => 'Modello inesistente'])->withInput();
			}

			// ✅ valida & normalizza
			[$clean, $err] = DynFormValidator::validate($modello, $payload);
			if ($err) return back()->withErrors($err)->withInput();

			if ($intervento->compilazione_id && $intervento->compilazione) {
				$intervento->compilazione->update([
					'modello_id'   => $modello->id,
					'version'      => $modello->version ?? 1,
					'payload_json' => $clean,
					'is_draft'     => false,
				]);
			} else {
				$compilazione = ModelloCompilazione::create([
					'modello_id'   => $modello->id,
					'target_type'  => ManutenzioneRegistro::class,
					'target_id'    => $intervento->id,
					'version'      => $modello->version ?? 1,
					'payload_json' => $clean,
					'is_draft'     => false,
				]);
				$intervento->update(['compilazione_id' => $compilazione->id]);
			}
		}

        return redirect()->route('manutenzioni.registro.index')->with('success', 'Intervento aggiornato');
    }

    public function destroy($id)
    {
        $intervento = ManutenzioneRegistro::findOrFail($id);
        $intervento->delete();

        return redirect()->route('manutenzioni.registro.index')->with('success', 'Intervento eliminato');
    }

    public function show($id)
    {
        return redirect()->route('manutenzioni.registro.edit', $id);
    }
	
}

