<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ManutenzioneCompetenza;

class ManutenzioniCompetenzeSeeder extends Seeder
{
    public function run(): void
    {
        ManutenzioneCompetenza::insert([
            [
                'nome' => 'Mario Rossi',
                'tipo' => 'interno',
                'contatti' => 'mario.rossi@azienda.local',
                'abilitazioni' => 'PES, Controllo estintori',
                'note' => 'Responsabile manutenzione interna',
            ],
            [
                'nome' => 'Sicuri S.r.l.',
                'tipo' => 'esterno',
                'contatti' => 'info@sicurisrl.it - Tel. 0123456789',
                'abilitazioni' => 'Manutenzione presidi antincendio UNI 9994-1',
                'note' => 'Ditta certificata per manutenzione estintori',
            ],
            [
                'nome' => 'Ditta GRU Impianti S.p.A.',
                'tipo' => 'esterno',
                'contatti' => 'assistenza@gruimpianti.it',
                'abilitazioni' => 'Verifica annuale gru, controllo funi',
                'note' => null,
            ],
        ]);
    }
}
