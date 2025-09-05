<?php

namespace App\Http\Controllers\DPI;

use App\Http\Controllers\Controller;
use App\Models\DpiTipo;
use Illuminate\Http\Request;

class DpiTipoController extends Controller
{
    public function index(){
        $tipi = DpiTipo::orderBy('nome')->paginate(20);
        return view('dpi.tipi.index', compact('tipi'));
    }

    public function create(){
        return view('dpi.tipi.create');
    }

    public function store(Request $r){
        $data = $r->validate([
            'nome' => 'required|string|max:255',
            'categoria' => 'nullable|in:I,II,III',
            'norma_en' => 'nullable|string|max:100',
            'rischi_coperti' => 'nullable|string',
            'politica_scadenza_default' => 'nullable|json',
            'note' => 'nullable|string',
            'attivo' => 'boolean',
        ]);
		
		// 🔧 Decode: da stringa JSON → array (solo se presente)
		if (!empty($data['politica_scadenza_default'])) {
			$data['politica_scadenza_default'] = json_decode($data['politica_scadenza_default'], true);
		}
		
		DpiTipo::create($data);
		return redirect()->route('dpi.tipi.index')->with('ok','Tipo DPI creato');
	}

    public function edit(DpiTipo $tipo){
        return view('dpi.tipi.edit', compact('tipo'));
    }

    public function update(Request $r, DpiTipo $tipo){
        $data = $r->validate([
            'nome' => 'required|string|max:255',
            'categoria' => 'nullable|in:I,II,III',
            'norma_en' => 'nullable|string|max:100',
            'rischi_coperti' => 'nullable|string',
            'politica_scadenza_default' => 'nullable|json',
            'note' => 'nullable|string',
            'attivo' => 'boolean',
        ]);
		
		if (array_key_exists('politica_scadenza_default', $data)) {
			$data['politica_scadenza_default'] = $data['politica_scadenza_default']
				? json_decode($data['politica_scadenza_default'], true)
				: null; // campo vuoto → NULL
		}
		
        $tipo->update($data);
        return redirect()->route('dpi.tipi.index')->with('ok','Tipo DPI aggiornato');
    }

    public function destroy(DpiTipo $tipo){
        $tipo->delete();
        return back()->with('ok','Tipo DPI eliminato');
    }
}
