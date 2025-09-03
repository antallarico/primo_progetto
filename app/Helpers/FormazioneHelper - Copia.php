<?php

namespace App\Helpers;

use Carbon\Carbon;
use App\Models\FormazioneCorso;
use App\Models\Formazione;


class FormazioneHelper
{
    public static function calcolaStatoFormazione($lavoratore_id, $competenza)
    {
        $oggi = Carbon::today();

        // Recupera ID dei corsi per la competenza specificata
        $corsi = FormazioneCorso::where('corso_competenza', $competenza)->pluck('id');

        // Recupera partecipazioni del lavoratore a quei corsi
        $partecipazioni = Formazione::with('corso')
			->where('lavoratore_id', $lavoratore_id)
			->whereHas('corso', function ($q) use ($competenza) {
				$q->where('corso_competenza', $competenza);
			})
			->orderByDesc('data_formazione')
			->get();


        if ($partecipazioni->isEmpty()) {
            return ['stato' => 'non svolta', 'scadenza' => null, 'ore_valide' => 0];
        }

        $corso_rappresentativo = $partecipazioni->first()->corso;

        // Caso Rolling a ore (es. Formazione Generale)
        if ($corso_rappresentativo->soglia_ore_rolling) {
            $finestra_anni = $corso_rappresentativo->rolling_finestra_anni ?? 5;
			$data_limite = $oggi->copy()->subYears($finestra_anni);
			
			$ore_valide = $partecipazioni->filter(function ($p) use ($data_limite, $oggi) {
			$data = \Carbon\Carbon::parse($p->data_formazione);
				return $data->between($data_limite, $oggi);
			})->sum(function ($p) {
				return $p->sessione?->durata_effettiva ?? 0;
			});
			
            $valide = $ore_valide >= $corso_rappresentativo->soglia_ore_rolling;

            return [
                'stato' => $valide ? 'valida' : 'scaduta',
                'scadenza' => null,
                'ore_valide' => $ore_valide
            ];
        }

        // Caso con scadenza fissa
        $ultima = $partecipazioni->first();
        $data_formazione = Carbon::parse($ultima->data_formazione);
        $validita_mesi = $ultima->corso->validita_mesi;

        if ($validita_mesi <= 0) {
            return ['stato' => 'valida', 'scadenza' => null];
        }

        $scadenza = $data_formazione->copy()->addMonths($validita_mesi);

        if ($scadenza < $oggi) {
            $stato = 'scaduta';
        } elseif ($oggi->diffInDays($scadenza) <= 60) {
            $stato = 'in scadenza';
        } else {
            $stato = 'valida';
        }

        return [
            'stato' => $stato,
            'scadenza' => $scadenza->format('Y-m-d'),
            'ore_valide' => null
        ];
    }
}
