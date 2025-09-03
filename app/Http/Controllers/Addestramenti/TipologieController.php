<?php

namespace App\Http\Controllers\Addestramenti;

use App\Http\Controllers\Controller;
use App\Models\AddestramentoTipologia;
use App\Models\ModelloDinamico;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TipologieController extends Controller
{
    public function index()
    {
        $tipologie = AddestramentoTipologia::with('modello')->orderBy('nome')->get();
        return view('addestramenti.tipologie.index', compact('tipologie'));
    }

    public function create()
    {
        $modelli = ModelloDinamico::where('modulo', 'Addestramenti')->orderBy('nome')->get();
        return view('addestramenti.tipologie.create', compact('modelli'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'           => 'required|string|max:191|unique:addestramenti_tipologie,nome',
            'descrizione'    => 'nullable|string',
            'modello_id'     => [
                'nullable','integer',
                Rule::exists('modelli_dinamici','id')->where(fn($q)=>$q->where('modulo','Addestramenti')),
            ],
            'validita_mesi'  => 'nullable|integer|min:1|max:120',
            'attiva'         => 'nullable|boolean',
        ]);
        $data['attiva'] = (bool)($data['attiva'] ?? true);

        AddestramentoTipologia::create($data);
        return redirect()->route('addestramenti.tipologie.index');
    }

    public function edit(AddestramentoTipologia $tipologia)
    {
        $modelli = ModelloDinamico::where('modulo', 'Addestramenti')->orderBy('nome')->get();
        return view('addestramenti.tipologie.edit', compact('tipologia','modelli'));
    }

    public function update(Request $request, AddestramentoTipologia $tipologia)
    {
        $data = $request->validate([
            'nome'           => ['required','string','max:191', Rule::unique('addestramenti_tipologie','nome')->ignore($tipologia->id)],
            'descrizione'    => 'nullable|string',
            'modello_id'     => [
                'nullable','integer',
                Rule::exists('modelli_dinamici','id')->where(fn($q)=>$q->where('modulo','Addestramenti')),
            ],
            'validita_mesi'  => 'nullable|integer|min:1|max:120',
            'attiva'         => 'nullable|boolean',
        ]);
        $data['attiva'] = (bool)($data['attiva'] ?? true);

        $tipologia->update($data);
        return redirect()->route('addestramenti.tipologie.index');
    }

    public function destroy(AddestramentoTipologia $tipologia)
    {
        $tipologia->delete();
        return redirect()->route('addestramenti.tipologie.index');
    }
}
