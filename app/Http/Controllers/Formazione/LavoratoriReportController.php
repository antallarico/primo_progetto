<?php

namespace App\Http\Controllers\Formazione;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Lavoratore;
use App\Models\Formazione;
use Carbon\Carbon;

class LavoratoriReportController extends Controller
{
    public function index()
    {
        $anni = range(2025, 2020);
        $lavoratori = Lavoratore::where('attivo', 1)
            ->orderBy('cognome')
            ->orderBy('nome')
            ->get();

        $formazioni = Formazione::with('sessione')
            ->whereHas('sessione', function ($query) {
                $query->whereNotNull('durata_effettiva');
            })
            ->get();

        $report = [];
        foreach ($lavoratori as $lavoratore) {
            foreach ($anni as $anno) {
                $ore = $formazioni
                    ->where('lavoratore_id', $lavoratore->id)
                    ->filter(function ($f) use ($anno) {
                        return Carbon::parse($f->data_formazione)->year == $anno;
                    })
                    ->sum(function ($f) {
                        return $f->sessione->durata_effettiva ?? 0;
                    });

                $report[$lavoratore->id][$anno] = number_format($ore, 1);
            }
        }

        return view('formazione.storico.lavoratori', [
            'lavoratori' => $lavoratori,
            'anni' => $anni,
            'formazioni' => $report,
        ]);
    }
}
