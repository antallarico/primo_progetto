<?php

namespace App\Http\Controllers\Addestramenti;

use App\Http\Controllers\Controller;
use App\Models\AddestramentoRegistro;
use App\Models\AddestramentoTipologia;
use App\Models\Attrezzatura;
use App\Models\Lavoratore;
use App\Models\ModelloDinamico;
use App\Support\DynFormValidator;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Carbon;

class RegistroController extends Controller
{
    public function index()
    {
        $addestramenti = AddestramentoRegistro::with(['lavoratore','tipologia','attrezzatura'])
            ->orderByDesc('data_addestramento')
            ->orderByDesc('id')
            ->get();

        return view('addestramenti.registro.index', compact('addestramenti'));
    }

    public function create(Request $request)
    {
        $attrezzaturaId = $request->integer('attrezzatura_id');
        $tipologiaId    = $request->integer('tipologia_id');

        $lavoratori  = Lavoratore::orderBy('cognome')->orderBy('nome')->get();
        $attrezzature= Attrezzatura::orderBy('nome')->get();
        $tipologie   = AddestramentoTipologia::where('attiva', true)->orderBy('nome')->get();
        $modelli     = ModelloDinamico::where('modulo','Addestramenti')->orderBy('nome')->get();

        $tipologiaSel = $tipologie->firstWhere('id', $tipologiaId);
        $modello      = $tipologiaSel?->modello ?? null;

        // per lo script di auto-preselezione modello da tipologia
        $tipologieMap = $tipologie->map(fn($t)=>['id'=>$t->id,'modello_id'=>$t->modello_id])->values();

        return view('addestramenti.registro.create', compact(
            'lavoratori','attrezzature','tipologie','modelli',
            'attrezzaturaId','tipologiaId','modello','tipologieMap'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'lavoratore_id'      => 'required|integer|exists:lavoratori,id',
            'tipologia_id'       => 'nullable|integer|exists:addestramenti_tipologie,id',
            'attrezzatura_id'    => 'nullable|integer|exists:attrezzature,id',
			'ambito'             => 'required|in:attrezzatura,tipologia,selezione',
            'data_addestramento' => 'required|date',
            'esito'              => ['nullable', Rule::in(['idoneo','non_idoneo','in_affiancamento'])],
            'istruttore_id'      => 'nullable|integer|exists:lavoratori,id',
            'istruttore_nome'    => 'nullable|string|max:191',
            'note'               => 'nullable|string',

            'modello_id'         => [
                'nullable','integer',
                Rule::exists('modelli_dinamici','id')->where(fn($q)=>$q->where('modulo','Addestramenti')),
            ],
            'payload'            => 'nullable|array',
			
			// ⬅️ NEW: lista attrezzature per ambito "selezione"
			'attrezzature_ids'   => 'nullable|array',
			'attrezzature_ids.*' => 'integer|exists:attrezzature,id',
        ]);
		
		// vincoli ambito
		$errors = [];
		if ($data['ambito'] === 'attrezzatura' && empty($data['attrezzatura_id'])) {
			$errors['attrezzatura_id'] = 'Obbligatorio quando l\'ambito è "Solo questa attrezzatura".';
		}
		if ($data['ambito'] === 'tipologia' && empty($data['tipologia_id'])) {
			$errors['tipologia_id'] = 'Obbligatorio quando l\'ambito è "Tutte della tipologia".';
		}
		if ($data['ambito'] === 'selezione' && empty($data['attrezzature_ids'])) {
			$errors['attrezzature_ids'] = 'Seleziona almeno un\'attrezzatura.';
		}
		if ($errors) return back()->withErrors($errors)->withInput();

		
		// DynForm
        $clean = null;
        if (!empty($data['modello_id'])) {
            $modello = ModelloDinamico::where('modulo','Addestramenti')->findOrFail($data['modello_id']);
            [$clean, $errors] = DynFormValidator::validate($modello, $data['payload'] ?? []);
            if (!empty($errors)) return back()->withErrors($errors)->withInput();
        }

        // calcola scadenza se tipologia ha validità
        $scade = null;
        if (!empty($data['tipologia_id'])) {
            $tipo = AddestramentoTipologia::find($data['tipologia_id']);
            if ($tipo && $tipo->validita_mesi) {
                $scade = Carbon::parse($data['data_addestramento'])->addMonths($tipo->validita_mesi)->toDateString();
            }
        }
		
		// create
        $rec = AddestramentoRegistro::create([
            'lavoratore_id'      => $data['lavoratore_id'],
            'tipologia_id'       => $data['tipologia_id'] ?? null,
            'attrezzatura_id'    => $data['attrezzatura_id'] ?? null,
			'ambito'             => $data['ambito'],
            'modello_id'         => $data['modello_id'] ?? null,
            'payload_json'       => $clean,
            'data_addestramento' => $data['data_addestramento'],
            'esito'              => $data['esito'] ?? null,
            'istruttore_id'      => $data['istruttore_id'] ?? null,
            'istruttore_nome'    => $data['istruttore_nome'] ?? null,
            'scade_il'           => $scade,
            'note'               => $data['note'] ?? null,
        ]);
	    
		// pivot (solo per "selezione")
		if ($data['ambito'] === 'selezione') {
			$rec->attrezzature()->sync($data['attrezzature_ids'] ?? []);
		}

        return redirect()->route('addestramenti.registro.edit', $rec);
    }

    public function edit(AddestramentoRegistro $addestramento)
    {
        $lavoratori  = Lavoratore::orderBy('cognome')->orderBy('nome')->get();
        $attrezzature= \App\Models\Attrezzatura::orderBy('nome')->get();
        $tipologie   = AddestramentoTipologia::where('attiva', true)->orderBy('nome')->get();
        $modelli     = ModelloDinamico::where('modulo','Addestramenti')->orderBy('nome')->get();

        $values  = old('payload', $addestramento->payload_json ?? []);
        $modello = $addestramento->modello;

        $tipologieMap = $tipologie->map(fn($t)=>['id'=>$t->id,'modello_id'=>$t->modello_id])->values();
		$selectedAttrezzature = $addestramento->attrezzature()->pluck('attrezzature.id')->all();
		
        return view('addestramenti.registro.edit', compact(
            'addestramento','lavoratori','attrezzature','tipologie','modelli','values','modello','tipologieMap','selectedAttrezzature'
        ));
    }

    public function update(Request $request, AddestramentoRegistro $addestramento)
    {
        $data = $request->validate([
            'lavoratore_id'      => 'required|integer|exists:lavoratori,id',
            'tipologia_id'       => 'nullable|integer|exists:addestramenti_tipologie,id',
            'attrezzatura_id'    => 'nullable|integer|exists:attrezzature,id',
			'ambito'             => 'required|in:attrezzatura,tipologia,selezione',
            'data_addestramento' => 'required|date',
            'esito'              => ['nullable', Rule::in(['idoneo','non_idoneo','in_affiancamento'])],
            'istruttore_id'      => 'nullable|integer|exists:lavoratori,id',
            'istruttore_nome'    => 'nullable|string|max:191',
            'note'               => 'nullable|string',

            'modello_id'         => [
                'nullable','integer',
                Rule::exists('modelli_dinamici','id')->where(fn($q)=>$q->where('modulo','Addestramenti')),
            ],
            'payload'            => 'nullable|array',
			
			// ⬅️ NEW: lista attrezzature per ambito "selezione"
			'attrezzature_ids'   => 'nullable|array',
			'attrezzature_ids.*' => 'integer|exists:attrezzature,id',
        ]);
		
		// vincoli ambito
		$errors = [];
		if ($data['ambito'] === 'attrezzatura' && empty($data['attrezzatura_id'])) {
			$errors['attrezzatura_id'] = 'Obbligatorio quando l\'ambito è "Solo questa attrezzatura".';
		}
		if ($data['ambito'] === 'tipologia' && empty($data['tipologia_id'])) {
			$errors['tipologia_id'] = 'Obbligatorio quando l\'ambito è "Tutte della tipologia".';
		}
		if ($data['ambito'] === 'selezione' && empty($data['attrezzature_ids'])) {
			$errors['attrezzature_ids'] = 'Seleziona almeno un\'attrezzatura.';
		}
		if ($errors) return back()->withErrors($errors)->withInput();

        $clean = null;
        if (!empty($data['modello_id'])) {
            $modello = ModelloDinamico::where('modulo','Addestramenti')->findOrFail($data['modello_id']);
            [$clean, $errors] = DynFormValidator::validate($modello, $data['payload'] ?? []);
            if (!empty($errors)) return back()->withErrors($errors)->withInput();
        }

        // ricalcola scadenza
        $scade = null;
        if (!empty($data['tipologia_id'])) {
            $tipo = AddestramentoTipologia::find($data['tipologia_id']);
            if ($tipo && $tipo->validita_mesi) {
                $scade = Carbon::parse($data['data_addestramento'])->addMonths($tipo->validita_mesi)->toDateString();
            }
        }

        $addestramento->update([
            'lavoratore_id'      => $data['lavoratore_id'],
            'tipologia_id'       => $data['tipologia_id'] ?? null,
            'attrezzatura_id'    => $data['attrezzatura_id'] ?? null,
			'ambito'             => $data['ambito'],
            'modello_id'         => $data['modello_id'] ?? null,
            'payload_json'       => $clean,
            'data_addestramento' => $data['data_addestramento'],
            'esito'              => $data['esito'] ?? null,
            'istruttore_id'      => $data['istruttore_id'] ?? null,
            'istruttore_nome'    => $data['istruttore_nome'] ?? null,
            'scade_il'           => $scade,
            'note'               => $data['note'] ?? null,
        ]);
		
		// pivot (solo per "selezione")
		if ($data['ambito'] === 'selezione') {
			$rec->attrezzature()->sync($data['attrezzature_ids'] ?? []);
		}

        return redirect()->route('addestramenti.registro.edit', $addestramento);
    }

    public function destroy(AddestramentoRegistro $addestramento)
    {
        $addestramento->delete();
        return redirect()->route('addestramenti.registro.index');
    }
}
