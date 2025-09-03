<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ManutenzioneTipologia;

class ManutenzioniTipologieSeeder extends Seeder
{
    public function run(): void
    {
        ManutenzioneTipologia::insert([
            [
                'nome' => 'Controllo mensile estintori',
                'descrizione' => 'Controllo visivo degli estintori da parte del manutentore interno',
                'periodicita_mesi' => 1,
                'obbligatoria' => true,
                'con_checklist' => true,
                'documentabile' => false,
                'note' => null,
            ],
            [
                'nome' => 'Verifica semestrale estintori (azienda esterna)',
                'descrizione' => 'Verifica tecnica da parte di ditta certificata',
                'periodicita_mesi' => 6,
                'obbligatoria' => true,
                'con_checklist' => true,
                'documentabile' => true,
                'note' => 'Richiede verbale firmato',
            ],
            [
                'nome' => 'Controllo scala portatile',
                'descrizione' => 'Controllo visivo delle condizioni di sicurezza',
                'periodicita_mesi' => 3,
                'obbligatoria' => false,
                'con_checklist' => true,
                'documentabile' => false,
                'note' => null,
            ],
            [
                'nome' => 'Verifica annuale gru a bandiera',
                'descrizione' => 'Verifica strutturale e funzionale annuale',
                'periodicita_mesi' => 12,
                'obbligatoria' => true,
                'con_checklist' => true,
                'documentabile' => true,
                'note' => 'Inserire anche esito tecnico e stato funi',
            ],
        ]);
    }
}
