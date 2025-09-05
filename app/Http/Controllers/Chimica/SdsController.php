<?php

namespace App\Http\Controllers\Chimica;

use App\Http\Controllers\Controller;
use App\Models\ProdottoChimico;
use App\Models\ProdottoChimicoSds;
use App\Models\ProdottoChimicoSdsClp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class SdsController extends Controller
{
    private array $allowedPictos = ['GHS01','GHS02','GHS03','GHS04','GHS05','GHS06','GHS07','GHS08','GHS09'];

    public function index(ProdottoChimico $prodotto)
    {
        $sds = $prodotto->sds()->with('clp')
            ->orderByDesc('data_revisione')->orderByDesc('id')
            ->get();

        $corrente = $sds->first(); // la più recente
        return view('chimica.sds.index', compact('prodotto','sds','corrente'));
    }

    public function store(Request $r, ProdottoChimico $prodotto)
    {
        $data = $r->validate([
            'data_revisione' => 'required|date',
            'rev_num'        => 'nullable|string|max:50',
            'lingua'         => 'required|string|max:5',
            'file'           => 'required|file|mimes:pdf|max:10240',
            // CLP
            'signal_word'    => 'required|in:Danger,Warning,None',
            'pittogrammi'    => 'array',
            'pittogrammi.*'  => 'in:'.implode(',', $this->allowedPictos),
            'frasi_h'        => 'nullable|string', // comma-separated
            'frasi_p'        => 'nullable|string', // comma-separated
        ]);

        return DB::transaction(function () use ($prodotto, $data, $r) {
            $path = $r->file('file')->store('chimica/sds/'.$prodotto->id, 'private');

            $sds = ProdottoChimicoSds::create([
                'prodotto_id'    => $prodotto->id,
                'data_revisione' => $data['data_revisione'],
                'rev_num'        => $data['rev_num'] ?? null,
                'lingua'         => $data['lingua'],
                'file_path'      => $path,
            ]);

            ProdottoChimicoSdsClp::create([
                'sds_id'       => $sds->id,
                'signal_word'  => $data['signal_word'],
                'pittogrammi'  => array_values($data['pittogrammi'] ?? []),
                'frasi_h'      => $this->csvToArray($data['frasi_h'] ?? ''),
                'frasi_p'      => $this->csvToArray($data['frasi_p'] ?? ''),
            ]);

            return redirect()->route('chimica.prodotti.sds.index', $prodotto)->with('ok', 'SDS caricata');
        });
    }

    /** Apri il PDF inline (senza download) */
    public function view(ProdottoChimicoSds $sds)
    {
        return response()->file(Storage::disk('private')->path($sds->file_path));
    }

    /** Pagina modifica SDS */
    public function edit(ProdottoChimicoSds $sds)
    {
        $sds->load('clp','prodotto');
        return view('chimica.sds.edit', compact('sds'));
    }

    /** Salva modifiche SDS (campi + CLP + eventuale nuovo PDF) */
    public function update(Request $r, ProdottoChimicoSds $sds)
    {
        $data = $r->validate([
            'data_revisione' => 'required|date',
            'rev_num'        => 'nullable|string|max:50',
            'lingua'         => 'required|string|max:5',
            'file'           => 'nullable|file|mimes:pdf|max:10240',
            // CLP
            'signal_word'    => 'required|in:Danger,Warning,None',
            'pittogrammi'    => 'array',
            'pittogrammi.*'  => 'in:'.implode(',', $this->allowedPictos),
            'frasi_h'        => 'nullable|string',
            'frasi_p'        => 'nullable|string',
        ]);

        return DB::transaction(function () use ($sds, $data, $r) {
            // Sostituzione PDF (opzionale)
            if ($r->hasFile('file')) {
                $old = $sds->file_path;
                $path = $r->file('file')->store('chimica/sds/'.$sds->prodotto_id, 'private');
                $sds->file_path = $path;
                if ($old && Storage::disk('private')->exists($old)) {
                    Storage::disk('private')->delete($old);
                }
            }

            // Aggiorna campi SDS
            $sds->fill([
                'data_revisione' => $data['data_revisione'],
                'rev_num'        => $data['rev_num'] ?? null,
                'lingua'         => $data['lingua'],
            ])->save();

            // Aggiorna/crea CLP
            $clp = $sds->clp()->firstOrNew();
            $clp->fill([
                'signal_word'  => $data['signal_word'],
                'pittogrammi'  => array_values($data['pittogrammi'] ?? []),
                'frasi_h'      => $this->csvToArray($data['frasi_h'] ?? ''),
                'frasi_p'      => $this->csvToArray($data['frasi_p'] ?? ''),
            ])->save();

            return redirect()
                ->route('chimica.prodotti.sds.index', $sds->prodotto)
                ->with('ok','SDS aggiornata');
        });
    }

    public function download(ProdottoChimicoSds $sds)
    {
        return Storage::disk('private')->download($sds->file_path, $this->filenameFor($sds));
    }

    public function destroy(ProdottoChimicoSds $sds)
    {
        DB::transaction(function() use ($sds){
            $path = $sds->file_path;
            $sds->delete();
            if ($path && Storage::disk('private')->exists($path)) {
                Storage::disk('private')->delete($path);
            }
        });
        return back()->with('ok','SDS eliminata');
    }

    private function csvToArray(string $csv): array
    {
        $csv = trim($csv);
        if ($csv === '') return [];
        return array_values(array_filter(array_map('trim', explode(',', $csv))));
    }

    private function filenameFor(ProdottoChimicoSds $sds): string
    {
        $base = preg_replace('/[^A-Za-z0-9_\-]+/', '_', $sds->prodotto->nome_commerciale);
        $rev  = $sds->rev_num ? '_'.$sds->rev_num : '';
        return $base.'_'.$sds->lingua.$rev.'.pdf';
    }
}
