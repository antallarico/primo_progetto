<?php

namespace App\Http\Controllers\Formazione;

use App\Http\Controllers\Controller;
use App\Models\Lavoratore;

class StoricoLavoratoreController extends Controller
{
    // Mostra l'elenco dei lavoratori con link allo storico formazione
    public function elencoLavoratori()
    {
        $lavoratori = Lavoratore::orderBy('cognome')->orderBy('nome')->get();
        return view('formazione.storico.lavoratori', compact('lavoratori'));
    }
}
