<?php

namespace App\Http\Controllers\DPI;

use App\Http\Controllers\Controller;
use App\Models\DpiArticolo;
use App\Models\DpiConsegna;
use App\Models\DpiTipo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DpiConsegnaController extends Controller
{
    public function index(Request $r)
    {
        $q = DpiConsegna::with(['lavoratore','articolo.tipo'])->latest('data_consegna');

        if ($r->filled('lavoratore_id')) $q->where('lavoratore_id', $r->lavoratore_id);
        if ($r->filled('stato')) $q->where('stato', $r->stato);
        if ($r->filled('tipo_id')) {
            $q->whereHas('articolo', fn($qa)=>$qa->where('tipo_id', $r->tipo_id));
        }

        $consegne = $q->paginate(20)->withQueryString();
        $tipi = DpiTipo::orderBy('nome')->get();

        // TODO: sostituisci con il tuo modello (Lavoratore o Dipendente)
        $lavoratori = \App\Models\Lavoratore::orderBy('cognome')->get();

        return view('dpi.consegne.index', compact('consegne','tipi','lavoratori'));
    }

    public function create()
    {
        $articoli = DpiArticolo::with('tipo')->where('attivo',1)->orderBy('marca')->orderBy('modello')->get();
        $lavoratori = \App\Models\Lavoratore::orderBy('cognome')->get(); // cambia se è Dipendente
        return view('dpi.consegne.create', compact('articoli','lavoratori'));
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'lavoratore_id' => 'required|exists:lavoratori,id', // cambia se è dipendenti
            'articolo_id' => 'required|exists:dpi_articoli,id',
            'quantita' => 'required|integer|min:1',
            'data_consegna' => 'required|date',
            'data_primo_utilizzo' => 'nullable|date',
            'data_scadenza' => 'nullable|date', // consentiamo override manuale
            'note' => 'nullable|string'
        ]);

        $articolo = DpiArticolo::with('tipo')->findOrFail($data['articolo_id']);

        return DB::transaction(function () use ($data, $articolo) {
            // Controllo stock
            if ($articolo->quantita_disponibile < $data['quantita']) {
                return back()->withErrors(['quantita' => 'Quantità non disponibile a stock.'])->withInput();
            }

            // Se non specificata, calcola scadenza
            $data['data_scadenza'] = $data['data_scadenza'] ?? $this->calcolaScadenza($articolo, $data['data_primo_utilizzo'] ?? null, $data['data_consegna']);

            $consegna = DpiConsegna::create([
                'lavoratore_id' => $data['lavoratore_id'],
                'articolo_id' => $data['articolo_id'],
                'quantita' => $data['quantita'],
                'data_consegna' => $data['data_consegna'],
                'data_primo_utilizzo' => $data['data_primo_utilizzo'] ?? null,
                'data_scadenza' => $data['data_scadenza'],
                'stato' => 'ATTIVA',
                'note' => $data['note'] ?? null,
            ]);

            // Scarico stock
            $articolo->decrement('quantita_disponibile', $data['quantita']);

            return redirect()->route('dpi.consegne.index')->with('ok','Consegna registrata');
        });
    }

    public function restituzione(DpiConsegna $consegna)
    {
        return DB::transaction(function () use ($consegna) {
            if ($consegna->stato !== 'ATTIVA') {
                return back()->withErrors(['stato'=>'La consegna non è attiva.']);
            }

            $consegna->update([
                'stato' => 'RESTITUITA',
                'motivo_chiusura' => 'restituzione',
            ]);

            // Rientro stock
            $consegna->articolo()->update([
                'quantita_disponibile' => DB::raw('quantita_disponibile + '.$consegna->quantita)
            ]);

            return back()->with('ok','Restituzione registrata');
        });
    }

    /**
     * “Sostituzione” nel senso pratico: chiudo la consegna attuale come SOSTITUITA
     * e creo una nuova consegna (anche stesso articolo/taglia), scalando stock.
     */
	
	public function sostituzione(Request $r, DpiConsegna $consegna)
	{
		$data = $r->validate([
			'nuovo_articolo_id' => 'required|exists:dpi_articoli,id',
			'quantita'          => 'required|integer|min:1',
			'data_consegna'     => 'required|date',
			'data_primo_utilizzo' => 'nullable|date',
			'data_scadenza'     => 'nullable|date',
			'motivo_chiusura'   => 'nullable|string|max:100', // es. usura
			'note'              => 'nullable|string',
		]);

		$articoloNuovo = DpiArticolo::with('tipo')->findOrFail($data['nuovo_articolo_id']);

		// ✅ Pre-check fuori dalla transazione: non toccare la riga vecchia se manca stock
		if ($articoloNuovo->quantita_disponibile < $data['quantita']) {
			return back()->withErrors(['quantita' => 'Quantità non disponibile a stock per la nuova consegna.'])->withInput();
		}

		try {
			DB::beginTransaction();

			// lock della consegna da chiudere
			$consegna->refresh(); // carica ultimi valori
			if ($consegna->stato !== 'ATTIVA') {
				throw new \RuntimeException('La consegna da sostituire non è attiva.');
			}

			// Chiudi la vecchia (no rientro stock per “usura/sostituzione”)
			$consegna->update([
				'stato'         => 'SOSTITUITA',
				'motivo_chiusura' => $data['motivo_chiusura'] ?? 'usura',
				'data_chiusura' => now()->toDateString(), // se hai aggiunto il campo
			]);

			// Calcola scadenza della nuova (se non fornita)
			$data['data_scadenza'] = $data['data_scadenza'] ?? $this->calcolaScadenza(
				$articoloNuovo,
				$data['data_primo_utilizzo'] ?? null,
				$data['data_consegna']
			);

			// Crea la NUOVA riga (✅ storico garantito)
			$nuova = DpiConsegna::create([
				'lavoratore_id'       => $consegna->lavoratore_id,
				'articolo_id'         => $articoloNuovo->id,
				'quantita'            => $data['quantita'],
				'data_consegna'       => $data['data_consegna'],
				'data_primo_utilizzo' => $data['data_primo_utilizzo'] ?? null,
				'data_scadenza'       => $data['data_scadenza'],
				'stato'               => 'ATTIVA',
				'note'                => $data['note'] ?? null,
				'sostituisce_id'      => $consegna->id, // 🔗 link esplicito alla precedente
			]);

			// Scala stock del NUOVO articolo
			$articoloNuovo->decrement('quantita_disponibile', $data['quantita']);

			DB::commit();
			return redirect()->route('dpi.consegne.index')->with('ok','Sostituzione registrata');
		} catch (\Throwable $e) {
			DB::rollBack();
			return back()->withErrors(['sostituzione' => 'Errore durante la sostituzione: '.$e->getMessage()])->withInput();
		}
	}
	
	 
/*	 
    public function sostituzione(Request $r, DpiConsegna $consegna)
    {
        $data = $r->validate([
            'nuovo_articolo_id' => 'required|exists:dpi_articoli,id',
            'quantita' => 'required|integer|min:1',
            'data_consegna' => 'required|date',
            'data_primo_utilizzo' => 'nullable|date',
            'data_scadenza' => 'nullable|date',
            'motivo_chiusura' => 'nullable|string|max:100', // es. usura
            'note' => 'nullable|string',
        ]);

        $articoloNuovo = DpiArticolo::with('tipo')->findOrFail($data['nuovo_articolo_id']);

        return DB::transaction(function () use ($consegna, $data, $articoloNuovo) {
            if ($consegna->stato !== 'ATTIVA') {
                return back()->withErrors(['stato'=>'La consegna da sostituire non è attiva.']);
            }

            // Chiudi la vecchia
            $consegna->update([
                'stato' => 'SOSTITUITA',
                'motivo_chiusura' => $data['motivo_chiusura'] ?? 'usura',
            ]);

            // Stock check
            if ($articoloNuovo->quantita_disponibile < $data['quantita']) {
                return back()->withErrors(['quantita'=>'Quantità non disponibile a stock per la nuova consegna.']);
            }

            // Scadenza per la nuova (se non specificata)
            $data['data_scadenza'] = $data['data_scadenza'] ?? $this->calcolaScadenza(
                $articoloNuovo,
                $data['data_primo_utilizzo'] ?? null,
                $data['data_consegna']
            );

            // Crea la nuova consegna
            DpiConsegna::create([
                'lavoratore_id' => $consegna->lavoratore_id,
                'articolo_id' => $articoloNuovo->id,
                'quantita' => $data['quantita'],
                'data_consegna' => $data['data_consegna'],
                'data_primo_utilizzo' => $data['data_primo_utilizzo'] ?? null,
                'data_scadenza' => $data['data_scadenza'],
                'stato' => 'ATTIVA',
                'note' => $data['note'] ?? null,
            ]);

            // Scarico stock del nuovo articolo
            $articoloNuovo->decrement('quantita_disponibile', $data['quantita']);

            return redirect()->route('dpi.consegne.index')->with('ok','Sostituzione registrata');
        });
    }
*/
    private function calcolaScadenza(DpiArticolo $articolo, ?string $dataPrimoUtilizzo, string $dataConsegna): ?string
    {
        // 1) priorità al valore del fabbricante sull'articolo
        if ($articolo->validita_mesi_default) {
            $base = $dataPrimoUtilizzo ?: $dataConsegna;
            return Carbon::parse($base)->addMonths($articolo->validita_mesi_default)->toDateString();
        }

        // 2) fallback alla politica del tipo
        $pol = $articolo->tipo->politica_scadenza_default ?: null;
        if (is_array($pol) && isset($pol['base'])) {
            $val = $pol['valore'] ?? null;
            switch ($pol['base']) {
                case 'mesi_dal_primo_utilizzo':
                    if ($val) return Carbon::parse($dataPrimoUtilizzo ?: $dataConsegna)->addMonths($val)->toDateString();
                    break;
                case 'mesi_dalla_consegna':
                    if ($val) return Carbon::parse($dataConsegna)->addMonths($val)->toDateString();
                    break;
                case 'nessuna_scadenza':
                    return null;
            }
        }

        return null; // se non definito nulla, nessuna scadenza
    }
}
