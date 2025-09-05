<?php

namespace App\Http\Controllers\DPI;

use App\Http\Controllers\Controller;
use App\Models\DpiArticolo;
use App\Models\DpiTipo;
use Illuminate\Http\Request;

class DpiArticoloController extends Controller
{
    public function index(Request $r){
        $q = DpiArticolo::with('tipo')->orderBy('marca')->orderBy('modello');
        if ($r->filled('tipo_id')) $q->where('tipo_id', $r->tipo_id);
        if ($r->filled('attivo')) $q->where('attivo', (bool)$r->attivo);
        $articoli = $q->paginate(20)->withQueryString();
        $tipi = DpiTipo::orderBy('nome')->get();
        return view('dpi.articoli.index', compact('articoli','tipi'));
    }

    public function create(){
        $tipi = DpiTipo::orderBy('nome')->get();
        return view('dpi.articoli.create', compact('tipi'));
    }

    public function store(Request $r){
        $data = $r->validate([
            'tipo_id' => 'required|exists:dpi_tipi,id',
            'marca' => 'required|string|max:150',
            'modello' => 'required|string|max:150',
            'taglia' => 'nullable|string|max:50',
            'codice_fornitore' => 'nullable|string|max:100',
            'ean_sku' => 'nullable|string|max:100',
            'quantita_disponibile' => 'required|integer|min:0',
            'validita_mesi_default' => 'nullable|integer|min:1|max:120',
            'note' => 'nullable|string',
            'attivo' => 'boolean',
        ]);
        DpiArticolo::create($data);
        return redirect()->route('dpi.articoli.index')->with('ok','Articolo DPI creato');
    }

    public function edit(DpiArticolo $articolo){
        $tipi = DpiTipo::orderBy('nome')->get();
        return view('dpi.articoli.edit', compact('articolo','tipi'));
    }

    public function update(Request $r, DpiArticolo $articolo){
        $data = $r->validate([
            'tipo_id' => 'required|exists:dpi_tipi,id',
            'marca' => 'required|string|max:150',
            'modello' => 'required|string|max:150',
            'taglia' => 'nullable|string|max:50',
            'codice_fornitore' => 'nullable|string|max:100',
            'ean_sku' => 'nullable|string|max:100',
            'quantita_disponibile' => 'required|integer|min:0',
            'validita_mesi_default' => 'nullable|integer|min:1|max:120',
            'note' => 'nullable|string',
            'attivo' => 'boolean',
        ]);
        $articolo->update($data);
        return redirect()->route('dpi.articoli.index')->with('ok','Articolo DPI aggiornato');
    }

    public function destroy(DpiArticolo $articolo){
        $articolo->delete();
        return back()->with('ok','Articolo DPI eliminato');
    }
}
