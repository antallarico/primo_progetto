<?php

namespace App\Http\Controllers\ModelliDinamici;

use App\Http\Controllers\Controller;
use App\Models\ModelloDinamico;
use Illuminate\Http\Request;

class ModelloDinamicoController extends Controller
{
    public function index()
    {
        $modelli = ModelloDinamico::orderBy('updated_at','desc')->get();
        return view('modelli_dinamici.index', compact('modelli'));
    }

    public function create()
    {
        return view('modelli_dinamici.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'nome'         => 'required|string|max:190',
            'modulo'       => 'required|string|max:190',
            'tipologia_id' => 'nullable|integer',
            'stato'        => 'required|in:bozza,pubblicato',
        ]);

        $schema = ['fields'=>[]];
        $layout = ['sections'=>[]];

        $modello = ModelloDinamico::create(array_merge($data, [
            'schema_json' => $schema,
            'layout_json' => $layout,
            'version'     => 1,
        ]));

        return redirect()->route('modelli_dinamici.edit', $modello)->with('success','Creato. Ora costruisci schema/layout.');
    }

    public function edit(ModelloDinamico $modelloDinamico)
    {
        $schema = $modelloDinamico->schema_json ?: ['fields'=>[]];
        $layout = $modelloDinamico->layout_json ?: ['sections'=>[]];

        return view('modelli_dinamici.builder', [
            'modello' => $modelloDinamico,
            'schema'  => $schema,
            'layout'  => $layout,
        ]);
    }

    public function update(Request $request, ModelloDinamico $modelloDinamico)
    {
        $data = $request->validate([
            'nome'         => 'required|string|max:190',
            'modulo'       => 'required|string|max:190',
            'tipologia_id' => 'nullable|integer',
            'stato'        => 'required|in:bozza,pubblicato',
            'schema_json'  => 'required|string',
            'layout_json'  => 'required|string',
        ]);

        try {
            $schema = json_decode($data['schema_json'], true, 512, JSON_THROW_ON_ERROR);
            $layout = json_decode($data['layout_json'], true, 512, JSON_THROW_ON_ERROR);
        } catch (\Throwable $e) {
            return back()->withErrors(['schema_json' => 'JSON non valido: '.$e->getMessage()])->withInput();
        }

        $modelloDinamico->update([
            'nome'         => $data['nome'],
            'modulo'       => $data['modulo'],
            'tipologia_id' => $data['tipologia_id'] ?? null,
            'stato'        => $data['stato'],
            'schema_json'  => $schema,
            'layout_json'  => $layout,
            // version rimane invariata qui (la alzeremo quando servirà il versioning esplicito)
        ]);

        return back()->with('success','Modello aggiornato.');
    }

    public function destroy(ModelloDinamico $modelloDinamico)
    {
        $modelloDinamico->delete();
        return redirect()->route('modelli_dinamici.index')->with('success','Eliminato.');
    }
	
	public function builder(int $id) {
		$modello = \App\Models\ModelloDinamico::findOrFail($id);
		return view('modelli_dinamici.builder', compact('modello'));
	}


    /**
     * Compatibilità: restituisce i campi “semplici” per l’integrazione legacy.
     * - Se esiste `contenuto` lo usa così com’è.
     * - Altrimenti derive da schema_json.fields -> [{label,tipo,opzioni}]
    public function campiJson(ModelloDinamico $modelloDinamico)
    {
        // legacy
        if (!empty($modelloDinamico->contenuto) && is_array($modelloDinamico->contenuto)) {
            return response()->json($modelloDinamico->contenuto);
        }

        // v2 → riduci schema_json a campi base
        $schema = $modelloDinamico->schema_json ?? ['fields'=>[]];
        $fields = [];
        foreach (($schema['fields'] ?? []) as $f) {
            // solo tipi semplici qui; composed/repeatable non sono esposti in legacy
            if (in_array($f['type'] ?? 'text', ['text','number','date','select','textarea','checkbox'])) {
                $fields[] = [
                    'label'   => $f['label'] ?? ($f['key'] ?? 'Campo'),
                    'tipo'    => $f['type'] ?? 'text',
                    'opzioni' => $f['options'] ?? [],
                ];
            }
        }
        return response()->json($fields);
    }
	*/
	public function showJson(int $id)
	{
		$m = ModelloDinamico::findOrFail($id);

		return response()->json([
			'id'      => $m->id,
			'nome'    => $m->nome,
			'schema'  => $m->schema_json ?: ['fields' => []],
			'layout'  => $m->layout_json ?: ['sections' => []],
			'version' => $m->version ?? 1,
			'stato'   => $m->stato ?? 'bozza',
		]);
	}
	
}
