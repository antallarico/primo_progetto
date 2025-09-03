<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\FormazioneCorso;
use App\Models\FormazioneSessione;
use App\Models\Formazione;
use App\Models\Lavoratore;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportaFormazione extends Command
{
    protected $signature = 'formazione:importa {file}';
    protected $description = 'Importa dati di formazione da un file Excel';

    public function handle()
    {
        $path = $this->argument('file');

        if (!file_exists($path)) {
            $this->error("File non trovato: $path");
            return 1;
        }

        $spreadsheet = IOFactory::load($path);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $header = array_map('strtolower', array_map('trim', $rows[1]));
        unset($rows[1]);

        $importati = 0;
        $errori = 0;

        foreach ($rows as $index => $row) {
            $data = array_combine($header, array_map('trim', $row));

            DB::beginTransaction();
            try {
                // Lavoratore
                $lavoratore = Lavoratore::where('codice_fiscale', $data['lavoratore_cf'])->first();
                if (!$lavoratore) {
                    throw new \Exception("Lavoratore non trovato (CF: {$data['lavoratore_cf']})");
                }

                // Corso
                $corso = FormazioneCorso::firstOrCreate(
                    ['codice' => $data['codice_corso']],
                    [
                        'titolo' => $data['titolo_corso'],
                        'durata_ore' => $data['durata_effettiva'],
                        'validita_mesi' => null,
                        'normato' => false,
                        'obbligatorio' => false,
                    ]
                );

                // Sessione
                $sessione = FormazioneSessione::firstOrCreate(
                    [
                        'corso_id' => $corso->id,
                        'data_sessione' => $data['data_sessione'],
                    ],
                    [
                        'durata_effettiva' => $data['durata_effettiva'],
                        'docente' => $data['docente'],
                        'soggetto_formatore' => $data['soggetto_formatore'],
                        'luogo' => $data['luogo'],
                    ]
                );

                // Partecipazione
                Formazione::create([
                    'lavoratore_id' => $lavoratore->id,
                    'sessione_id' => $sessione->id,
                    'data_formazione' => $data['data_formazione'],
                    'data_scadenza' => $data['data_scadenza'] ?: null,
                    'attestato' => $data['attestato'] ?: 'non_previsto',
                    'link_attestato' => $data['link_attestato'],
                ]);

                DB::commit();
                $importati++;

            } catch (\Throwable $e) {
                DB::rollBack();
                $errori++;
                Log::error("Errore alla riga $index: " . $e->getMessage());
            }
        }

        $this->info("Importazione completata. Record importati: $importati, errori: $errori");
        return 0;
    }
}
