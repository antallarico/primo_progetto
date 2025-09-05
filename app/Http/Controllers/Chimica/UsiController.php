<?php

namespace App\Http\Controllers\Chimica;

use App\Http\Controllers\Controller;
use App\Models\ProdottoChimico;
use App\Models\ProdottoChimicoUso;
use Illuminate\Http\Request;

class UsiController extends Controller
{
    public function index(ProdottoChimico $prodotto)
    {
        $usi = $prodotto->hasMany(ProdottoChimicoUso::class, 'prodotto_id')
                        ->orderBy('area')->orderBy('processo')->get();

        // lista attrezzature se il model esiste
        $attrezzature = class_exists(\App\Models\Attrezzatura::class)
            ? \App\Models\Attrezzatura::orderBy('nome')->get(['id','nome'])
            : collect();

        return view('chimica.usi.index', compact('prodotto','usi','attrezzature'));
    }

    public function store(Request $r, ProdottoChimico $prodotto)
    {
        $data = $r->validate([
            'area'            => 'nullable|string|max:150',
            'attrezzatura_id' => 'nullable|integer|exists:attrezzature,id',
            'processo'        => 'nullable|string|max:255',
            'consumo_medio'   => 'nullable|numeric|min:0',
            'unita'           => 'nullable|in:L,mL,kg,g,pz',
            'note'            => 'nullable|string',
        ]);
        $data['prodotto_id'] = $prodotto->id;

        ProdottoChimicoUso::create($data);
        return back()->with('ok','Uso aggiunto');
    }
	
	public function edit(ProdottoChimico $prodotto, ProdottoChimicoUso $uso)
	{
		abort_unless($uso->prodotto_id === $prodotto->id, 404);

		$attrezzature = class_exists(\App\Models\Attrezzatura::class)
			? \App\Models\Attrezzatura::orderBy('nome')->get(['id','nome'])
			: collect();

		return view('chimica.usi.edit', compact('prodotto','uso','attrezzature'));
	}

	public function update(Request $r, ProdottoChimico $prodotto, ProdottoChimicoUso $uso)
	{
		abort_unless($uso->prodotto_id === $prodotto->id, 404);

		$data = $r->validate([
			'area'            => 'nullable|string|max:150',
			'attrezzatura_id' => 'nullable|integer|exists:attrezzature,id',
			'processo'        => 'nullable|string|max:255',
			'consumo_medio'   => 'nullable|numeric|min:0',
			'unita'           => 'nullable|in:L,mL,kg,g,pz',
			'note'            => 'nullable|string',
		]);

		$uso->update($data);

		return redirect()->route('chimica.prodotti.usi.index', $prodotto)->with('ok','Uso aggiornato');
	}


    public function destroy(ProdottoChimico $prodotto, ProdottoChimicoUso $uso)
    {
        abort_unless($uso->prodotto_id === $prodotto->id, 404);
        $uso->delete();
        return back()->with('ok','Uso eliminato');
    }
}
