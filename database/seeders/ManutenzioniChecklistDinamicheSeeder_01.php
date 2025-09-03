<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ManutenzioneChecklistDinamica;
use App\Models\ManutenzioneTipologia;

class ManutenzioniChecklistDinamicheSeeder extends Seeder
{
    public function run(): void
    {
        $tipologia = ManutenzioneTipologia::where('nome', 'Controllo mensile estintori')->first();

        if ($tipologia) {
            ManutenzioneChecklistDinamica::create([
                'tipologia_id' => $tipologia->id,
                'contenuto' => json_encode([
                    ['voce' => 'Verifica presenza estintore', 'obbligatoria' => true],
                    ['voce' => 'Controllo pressione manometro', 'obbligatoria' => true],
                    ['voce' => 'Etichetta leggibile e presente', 'obbligatoria' => false],
                ])
            ]);
        }
    }
}
