<?php

namespace App\Http\Controllers\Chimica;

use App\Http\Controllers\Controller;
use App\Models\ProdottoChimico;
use App\Models\ProdottoChimicoUbicazione;
use Illuminate\Http\Request;

class UbicazioniController extends Controller
{
    public function index(ProdottoChimico $prodotto)
    {
        $ubicazioni = $prodotto->hasMany(ProdottoChimicoUbicazione::class, 'prodotto_id')
                               ->orderBy('ubicazione')->get();
        return view('chimica.ubicazioni.index', compact('prodotto','ubicazioni'));
    }

    public function store(Request $r, ProdottoChimico $prodotto)
    {
        $data = $r->validate([
            'ubicazione'          => 'required|string|max:150',
            'quantita_disponibile'=> 'required|numeric|min:0',
            'unita'               => 'required|in:L,mL,kg,g,pz',
            'note'                => 'nullable|string',
        ]);
        $data['prodotto_id'] = $prodotto->id;

        ProdottoChimicoUbicazione::create($data);
        return back()->with('ok','Ubicazione aggiunta');
    }
	
	public function edit(ProdottoChimico $prodotto, ProdottoChimicoUbicazione $ubicazione)
	{
		abort_unless($ubicazione->prodotto_id === $prodotto->id, 404);
		return view('chimica.ubicazioni.edit', compact('prodotto','ubicazione'));
	}

	public function update(Request $r, ProdottoChimicoUbicazione $ubicazione)
	{
		$data = $r->validate([
			'ubicazione'          => 'sometimes|required|string|max:150',
			'quantita_disponibile'=> 'required|numeric|min:0',
			'unita'               => 'sometimes|required|in:L,mL,kg,g,pz',
			'note'                => 'nullable|string',
		]);

		$ubicazione->update($data);

		return redirect()
			->route('chimica.prodotti.ubicazioni.index', $ubicazione->prodotto)
			->with('ok','Ubicazione aggiornata');
	}


    public function destroy(ProdottoChimico $prodotto, ProdottoChimicoUbicazione $ubicazione)
    {
        abort_unless($ubicazione->prodotto_id === $prodotto->id, 404);
        $ubicazione->delete();
        return back()->with('ok','Ubicazione eliminata');
    }
}
