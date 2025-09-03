<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Attrezzatura;
use App\Models\AttrezzaturaCategoria;
use App\Models\AttrezzaturaTipologia;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpSpreadsheet\IOFactory;
use Carbon\Carbon;

class ImportaAttrezzature extends Command
{
    protected $signature = 'attrezzature:importa {path}';
    protected $description = 'Importa attrezzature da un file Excel specificando il path completo (es. storage/app/file.xlsx)';

    public function handle()
    {
        $path = $this->argument('path');

        if (!file_exists($path)) {
            $this->error("File non trovato: $path");
            return 1;
        }

        $spreadsheet = IOFactory::load($path);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        // Estrai intestazioni dalla prima riga
        $header = array_map('strtolower', array_map('trim', $rows[1]));
        unset($rows[1]);

        $importati = 0;
        $scartati = 0;

        foreach ($rows as $index => $row) {
            $data = array_combine($header, array_map('trim', $row));

            try {
                $categoria_id = null;
                if (!empty($data['categoria'])) {
                    $categoria_id = AttrezzaturaCategoria::firstOrCreate(['nome' => $data['categoria']])->id;
                }

                $tipologia_id = null;
                if (!empty($data['tipologia'])) {
                    $tipologia_id = AttrezzaturaTipologia::firstOrCreate(['nome' => $data['tipologia']])->id;
                }

                Attrezzatura::create([
                    'nome' => $data['nome'] ?? null,
                    'marca' => $data['marca'] ?? null,
                    'modello' => $data['modello'] ?? null,
                    'matricola' => $data['matricola'] ?? null,
                    'data_fabbricazione' => $this->parseDate($data['data_fabbricazione'] ?? null),
                    'ubicazione' => $data['ubicazione'] ?? null,
                    'stato' => $data['stato'] ?? null,
                    //'dich_ce' => $data['dich_ce'] ?? null,
					'dich_ce' => $data['dich_ce'] === '' ? null : $data['dich_ce'],
                    'tipo' => $data['tipo'] ?? null,
                    //'attrezzatura_padre_id' => $data['attrezzatura_padre_id'] ?? null,
					'attrezzatura_padre_id' => $data['attrezzatura_padre_id'] === '' ? null : (int) $data['attrezzatura_padre_id'],
                    'note' => $data['note'] ?? null,
                    'categoria_id' => $categoria_id,
                    'tipologia_id' => $tipologia_id,
                ]);

                $importati++;
            } catch (\Throwable $e) {
                $scartati++;
                Log::error("Errore importazione riga {$index}: " . $e->getMessage());
            }
        }

        $this->info("Importazione completata da $path. Attrezzature importate: $importati, scartate: $scartati");
        return 0;
    }

    private function parseDate($value)
    {
        if (!$value) return null;

        try {
            return Carbon::parse($value);
        } catch (\Exception $e) {
            return null;
        }
    }
}
