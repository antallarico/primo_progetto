<?php

namespace App\Http\Controllers\Chimica;

use App\Http\Controllers\Controller;
use App\Models\ProdottoChimico;
use Illuminate\Http\Request;

class ProdottoChimicoController extends Controller
{
    public function index(Request $r)
    {
        $q = ProdottoChimico::query()->with('sdsAttuale');

        if ($r->filled('q')) {
            $q->where(function($qq) use ($r){
                $qq->where('nome_commerciale','like','%'.$r->q.'%')
                   ->orWhere('fornitore','like','%'.$r->q.'%')
                   ->orWhere('codice_interno','like','%'.$r->q.'%');
            });
        }
        if ($r->filled('stato')) $q->where('stato', (bool)$r->stato);

        $prodotti = $q->orderBy('nome_commerciale')->paginate(20)->withQueryString();
        return view('chimica.prodotti.index', compact('prodotti'));
    }

    public function create()
    {
        return view('chimica.prodotti.create');
    }

    public function store(Request $r)
    {
        $data = $r->validate([
            'nome_commerciale' => 'required|string|max:255',
            'tipo' => 'required|in:sostanza,miscela',
            'codice_interno' => 'nullable|string|max:100',
            'codice_fornitore' => 'nullable|string|max:100',
            'fornitore' => 'nullable|string|max:255',
            'ufi' => 'nullable|string|max:25',
            'stato' => 'boolean',
            'note' => 'nullable|string',
        ]);
        ProdottoChimico::create($data);
        return redirect()->route('chimica.prodotti.index')->with('ok', 'Prodotto creato');
    }

    public function edit(ProdottoChimico $prodotto)
    {
        return view('chimica.prodotti.edit', compact('prodotto'));
    }

    public function update(Request $r, ProdottoChimico $prodotto)
    {
        $data = $r->validate([
            'nome_commerciale' => 'required|string|max:255',
            'tipo' => 'required|in:sostanza,miscela',
            'codice_interno' => 'nullable|string|max:100',
            'codice_fornitore' => 'nullable|string|max:100',
            'fornitore' => 'nullable|string|max:255',
            'ufi' => 'nullable|string|max:25',
            'stato' => 'boolean',
            'note' => 'nullable|string',
        ]);
        $prodotto->update($data);
        return redirect()->route('chimica.prodotti.index')->with('ok', 'Prodotto aggiornato');
    }

    public function destroy(ProdottoChimico $prodotto)
    {
        $prodotto->delete();
        return back()->with('ok','Prodotto eliminato');
    }
}
