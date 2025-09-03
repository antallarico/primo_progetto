<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class ManutenzioniChecklistDinamicheSeeder extends Seeder
{
    public function run()
    {
        DB::table('manutenzioni_checklist_dinamiche')->insert([
			[
                'tipologia_id' => 2,
                'contenuto' => json_encode([
                    ['descrizione' => 'Controllo visivo generale'],
                    ['descrizione' => 'Verifica condizioni generali dell’attrezzatura'],
                    ['descrizione' => 'Presenza di etichette e marcature'],
                    ['descrizione' => 'Verifica integrità strutturale']
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'tipologia_id' => 3,
                'contenuto' => json_encode([
                    ['descrizione' => 'Controllo livelli olio e lubrificanti'],
                    ['descrizione' => 'Pulizia filtri e condotti'],
                    ['descrizione' => 'Verifica corretto funzionamento dei comandi']
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ],
            [
                'tipologia_id' => 4,
                'contenuto' => json_encode([
                    ['descrizione' => 'Test di accensione/spegnimento'],
                    ['descrizione' => 'Controllo presenza perdite'],
                    ['descrizione' => 'Verifica stato cavi e collegamenti']
                ]),
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]						
        ]);
    }
}


