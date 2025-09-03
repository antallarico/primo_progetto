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
    protected $signature = 'attrezzature:importa {filename}';
    protected $description = 'Importa attrezzature da un file Excel posto in storage/app/{filename}';

    public function handle()
    {
        $filename = $this->argument('filename');
        $path = storage_path('app/' . $filename);

        if (!file_exists($path)) {
            $this->error("File non trovato: $filename in storage/app");
            return 1;
        }

        $spreadsheet = IOFactory::load($path);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $header = array_shift($rows);
        $importati = 0;
        $scartati = 0;

        foreach ($rows as $index => $row) {
            try {
                $categoria = trim($row['N'] ?? '');
                $tipologia = trim($row['O'] ?? '');

                $categoria_id = null;
                if ($categoria) {
                    $categoria_id = AttrezzaturaCategoria::firstOrCreate(['nome' => $categoria])->id;
                }

                $tipologia_id = null;
                if ($tipologia) {
                    $tipologia_id = AttrezzaturaTipologia::firstOrCreate(['nome' => $tipologia])->id;
                }

                Attrezzatura::create([
                    'nome' => $row['A'] ?? null,
                    'marca' => $row['B'] ?? null,
                    'modello' => $row['C'] ?? null,
                    'matricola' => $row['D'] ?? null,
                    'data_fabbricazione' => $this->parseDate($row['E'] ?? null),
                    'ubicazione' => $row['F'] ?? null,
                    'stato' => $row['G'] ?? null,
                    'dich_ce' => $row['H'] ?? null,
                    'tipo' => $row['I'] ?? null,
                    'attrezzatura_padre_id' => $row['J'] ?? null,
                    'note' => $row['K'] ?? null,
                    'categoria_id' => $categoria_id,
                    'tipologia_id' => $tipologia_id,
                ]);

                $importati++;
            } catch (\Throwable $e) {
                Log::error("Errore importazione riga {$index}: " . $e->getMessage());
                $scartati++;
            }
        }

        $this->info("Importazione completata da $filename. Attrezzature importate: $importati, scartate: $scartati");
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
