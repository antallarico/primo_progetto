<?php

namespace App\Http\Controllers\Attrezzature;

use App\Http\Controllers\Controller;
use App\Models\Attrezzatura;
use App\Models\AttrezzaturaCategoria;
use App\Models\AttrezzaturaTipologia;
use App\Models\ModelloDinamico;
use App\Models\ModelloCompilazione; // tabella: modelli_compilazioni
use App\Support\DynFormValidator;
use Illuminate\Validation\Rule;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AttrezzaturaController extends Controller
{
	
	public function index(Request $request)
	{
		$attrezzature = Attrezzatura::with(['categoria','attrezzaturaPadre','tipologia'])
			->withCount([
				'compilazioni as scheda_compilazioni_count' => function ($q) {
					$q->whereHas('modello', fn ($qq) => $qq->where('modulo', 'Attrezzature'));
				},
			])
			->orderBy('nome')->orderBy('id')
			->get();
	
		$categorie = AttrezzaturaCategoria::orderBy('nome')->get();
		$tipologie = AttrezzaturaTipologia::orderBy('nome')->get();

		return view('attrezzature.index', compact('attrezzature','categorie','tipologie'));
	}

    public function create()
    {
        $categorie  = AttrezzaturaCategoria::orderBy('nome')->get();
        $padri      = Attrezzatura::orderBy('nome')->get();
        $tipologie  = AttrezzaturaTipologia::orderBy('nome')->get();
        $modelli    = ModelloDinamico::where('modulo', 'Attrezzature')->orderBy('nome')->get();

        return view('attrezzature.create', compact('categorie', 'padri', 'tipologie', 'modelli'));
    }	
	
	
    public function store(Request $request)
    {
        // ✅ Validazione campi base + modello_id/payload per DynForm
        $data = $request->validate([
            'nome'                   => 'required|string|max:255',
            'marca'                  => 'nullable|string|max:255',
            'modello'                => 'nullable|string|max:255',
            'matricola'              => 'nullable|string|max:255',
            'data_fabbricazione'     => 'nullable|date',
            'ubicazione'             => 'nullable|string|max:255',
            'stato'                  => 'required|string|in:in uso,fuori uso,dismessa',
            'note'                   => 'nullable|string',
            'categoria_id'           => 'nullable|exists:attrezzature_categorie,id',
            'attrezzatura_padre_id'  => 'nullable|exists:attrezzature,id',
            'tipo'                   => 'nullable|string|max:255',
            'dich_ce'                => 'nullable|in:1,0',
            'tipologia_id'           => 'nullable|exists:attrezzature_tipologie,id',
			'matricola_azienda' => 'nullable|string|max:64|unique:attrezzature,matricola_azienda',

            // DynForm
            'modello_id'             => [
                'nullable','integer',
                Rule::exists('modelli_dinamici','id')->where(fn($q) => $q->where('modulo','Attrezzature')),
            ],
            'payload'                => ['nullable','array'],
        ]);

        $data['dich_ce'] = match ($request->input('dich_ce')) {
            '1' => true,
            '0' => false,
            default => null,
        };

        // se presente, salva anche il modello scelto sull'attrezzatura (colonna nullable)
        $data['modello_id'] = $data['modello_id'] ?? null;

        // 1) Crea attrezzatura
        $attrezzatura = Attrezzatura::create($data);

        // 2) Se scelto un modello, valida payload e upsert in modelli_compilazioni
        if (!empty($data['modello_id'])) {
            $this->validateAndUpsertDynformCompilazione(
                modelloId: $data['modello_id'],
                payload: $data['payload'] ?? [],
                attrezzatura: $attrezzatura
            );
        }

        return redirect()->route('attrezzature.index');
    }

	public function edit(Attrezzatura $attrezzatura)
	{
		$categorie = AttrezzaturaCategoria::orderBy('nome')->get();
		$padri     = Attrezzatura::where('id', '!=', $attrezzatura->id)->orderBy('nome')->get();
		$tipologie = AttrezzaturaTipologia::orderBy('nome')->get();

		// ⬇️ come nella full page: modelli filtrati sulla tipologia dell'attrezzatura (se presente)
		$modelli = ModelloDinamico::where('modulo', 'Attrezzature')
			->when($attrezzatura->tipologia_id, fn($q) => $q->where('tipologia_id', $attrezzatura->tipologia_id))
			->orderBy('nome')
			->get();

		// ⬇️ ultima compilazione (corrente) e relativo modello
		$compilazione = ModelloCompilazione::where('target_type', Attrezzatura::class)
			->where('target_id', $attrezzatura->id)
			->latest('id')
			->first();

		$modello = $compilazione ? $compilazione->modello : null;

		return view('attrezzature.edit', compact(
			'attrezzatura', 'categorie', 'padri', 'tipologie',
			'modelli', 'compilazione', 'modello'
		));
	}


    public function update(Request $request, Attrezzatura $attrezzatura)
    {
        // ✅ Validazione campi base + modello_id/payload per DynForm
        $data = $request->validate([
            'nome'                   => 'required|string|max:255',
            'marca'                  => 'nullable|string|max:255',
            'modello'                => 'nullable|string|max:255',
            'matricola'              => 'nullable|string|max:255',
            'data_fabbricazione'     => 'nullable|date',
            'ubicazione'             => 'nullable|string|max:255',
            'stato'                  => 'required|string|in:in uso,fuori uso,dismessa',
            'note'                   => 'nullable|string',
            'categoria_id'           => 'nullable|exists:attrezzature_categorie,id',
            'attrezzatura_padre_id'  => 'nullable|exists:attrezzature,id',
            'tipo'                   => 'nullable|string|max:255',
            'dich_ce'                => 'nullable|in:1,0',
            'tipologia_id'           => 'nullable|exists:attrezzature_tipologie,id',
			'matricola_azienda' => [
				'nullable','string','max:64',
				Rule::unique('attrezzature','matricola_azienda')->ignore($attrezzatura->id),
			],

            // DynForm
            'modello_id'             => [
                'nullable','integer',
                Rule::exists('modelli_dinamici','id')->where(fn($q) => $q->where('modulo','Attrezzature')),
            ],
            'payload'                => ['nullable','array'],
        ]);

        $data['dich_ce'] = match ($request->input('dich_ce')) {
            '1' => true,
            '0' => false,
            default => null,
        };

        // aggiorna anche l’eventuale modello scelto
        $data['modello_id'] = $data['modello_id'] ?? null;

        // 1) Aggiorna attrezzatura
        $attrezzatura->update($data);

        // 2) Se scelto un modello, valida payload e upsert in modelli_compilazioni
        if (!empty($data['modello_id'])) {
            $this->validateAndUpsertDynformCompilazione(
                modelloId: $data['modello_id'],
                payload: $data['payload'] ?? [],
                attrezzatura: $attrezzatura
            );
        }
        // Se modello_id diventa null NON cancelliamo lo storico; se serve, prevedere logica di archiviazione.

        return redirect()->route('attrezzature.index');
    }

    public function destroy(Attrezzatura $attrezzatura)
    {
        $attrezzatura->delete();
        return redirect()->route('attrezzature.index');
    }

    /**
     * Valida il payload rispetto allo schema del ModelloDinamico e fa upsert su modelli_compilazioni
     */
    private function validateAndUpsertDynformCompilazione(int $modelloId, array $payload, Attrezzatura $attrezzatura): void
	{
		$modello = ModelloDinamico::where('modulo','Attrezzature')->findOrFail($modelloId);

		// ✅ valida e ottieni [clean, errors]
		[$clean, $errors] = \App\Support\DynFormValidator::validate($modello, $payload);

		// Se ci sono errori, solleva una ValidationException (Laravel farà redirect back con gli errori)
		if (!empty($errors)) {
			throw ValidationException::withMessages($errors);
		}

		// Salva **il payload normalizzato** (non quello grezzo)
		\App\Models\ModelloCompilazione::updateOrCreate(
			[
				'target_type' => Attrezzatura::class,
				'target_id'   => $attrezzatura->id,
				'modello_id'  => $modello->id,
			],
			[
				'payload_json' => $clean,
			]
		);
	}
	

	public function quadro()
	{
		$attrezzature = \App\Models\Attrezzatura::with(['tipologia'])
			->withCount([
				// quante schede (compilazioni) con modello del modulo "Attrezzature"
				'compilazioni as scheda_compilazioni_count' => function ($q) {
					$q->whereHas('modello', fn ($qq) => $qq->where('modulo', 'Attrezzature'));
				},
			])
			// numero interventi registrati (usa direttamente la tabella manutenzioni_registro)
			->addSelect([
				'interventi_count' => DB::table('manutenzioni_registro')
					->selectRaw('COUNT(*)')
					->whereColumn('attrezzatura_id', 'attrezzature.id'),
			])
			->orderBy('nome')->orderBy('id')
			->get();

		return view('attrezzature.quadro', compact('attrezzature'));
	}

}
