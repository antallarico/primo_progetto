<?php

namespace App\Http\Controllers\ModelliDinamici;

use App\Http\Controllers\Controller;
use App\Models\ModelloDinamico;
use App\Models\ModelloDinamicoCompilazione;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CompilazioneController extends Controller
{
    public function store(Request $request) {
        $data = $request->validate([
            'modello_id'  => 'required|integer|exists:modelli_dinamici,id',
            'target_type' => 'required|string',
            'target_id'   => 'required|integer',
            'payload'     => 'required|array',
            'is_draft'    => 'nullable|boolean',
        ]);

        $modello = ModelloDinamico::findOrFail($data['modello_id']);

        // Validazione lato server in base a schema_json (MVP: required, min, max, options)
        $this->validateBySchema($data['payload'], $modello->schema_json);

        $comp = ModelloCompilazione::create([
            'modello_id'  => $modello->id,
            'target_type' => $data['target_type'],
            'target_id'   => $data['target_id'],
            'payload_json'=> $data['payload'],
            'version'     => $modello->version,
            'submitted_by'=> optional($request->user())->id,
            'is_draft'    => $data['is_draft'] ?? false,
        ]);

        return response()->json(['id'=>$comp->id], 201);
    }

    public function update(Request $request, int $id) {
        $comp = ModelloCompilazione::findOrFail($id);
        $data = $request->validate([
            'payload'  => 'required|array',
            'is_draft' => 'nullable|boolean',
        ]);
        $modello = $comp->modello;

        $this->validateBySchema($data['payload'], $modello->schema_json);

        $comp->update([
            'payload_json' => $data['payload'],
            'is_draft'     => $data['is_draft'] ?? $comp->is_draft,
        ]);

        return response()->json(['ok'=>true]);
    }

    /** MVP validator: required/min/max/options + supporto composed/repeatable */
    private function validateBySchema(array $payload, array $schema): void {
        $fields = $schema['fields'] ?? [];

        $errors = [];

        $validateField = function($f, $value, $path) use (&$errors, &$validateField) {
            $type = $f['type'] ?? 'text';
            $req  = $f['required'] ?? false;
            $val  = $f['validations'] ?? [];

            if ($req && (is_null($value) || $value === '' || $value === [])) {
                $errors[$path][] = 'Campo obbligatorio.';
                return;
            }
            if ($value === null || $value === '' || $value === []) return;

            if ($type === 'number') {
                if (!is_numeric($value)) $errors[$path][] = 'Deve essere numerico.';
                if (isset($val['min']) && $value < $val['min']) $errors[$path][] = 'Valore troppo basso.';
                if (isset($val['max']) && $value > $val['max']) $errors[$path][] = 'Valore troppo alto.';
            }
            if ($type === 'select') {
                if (isset($f['options']) && !in_array($value, $f['options'], true)) {
                    $errors[$path][] = 'Valore non ammesso.';
                }
            }
            if ($type === 'composed') {
                $schema = $f['schema'] ?? [];
                foreach ($schema as $sub) {
                    $name = $sub['name'];
                    $validateField($sub, $value[$name] ?? null, "$path.$name");
                }
            }
            if ($type === 'repeatable') {
                $item = $f['item']['schema'] ?? [];
                if (!is_array($value)) { $errors[$path][] = 'Deve essere una lista.'; return; }
                foreach ($value as $i => $row) {
                    foreach ($item as $sub) {
                        $name = $sub['name'];
                        $validateField($sub, $row[$name] ?? null, "$path[$i].$name");
                    }
                }
            }
        };

        foreach ($fields as $f) {
            $key = $f['key'];
            $validateField($f, $payload[$key] ?? null, $key);
        }

        if (!empty($errors)) {
            throw ValidationException::withMessages($errors);
        }
    }
}
