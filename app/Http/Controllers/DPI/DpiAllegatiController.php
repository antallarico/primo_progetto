<?php

namespace App\Http\Controllers\DPI;

use App\Http\Controllers\Controller;
use App\Models\DpiConsegna;
use App\Models\DpiConsegnaAllegato;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DpiAllegatiController extends Controller
{
    public function store(Request $r, DpiConsegna $consegna)
    {
        $data = $r->validate([
            'file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:5120'
        ]);
        $path = $r->file('file')->store('dpi/consegne/'.$consegna->id, 'private');

        $consegna->allegati()->create([
            'nome_file' => $r->file('file')->getClientOriginalName(),
            'path' => $path
        ]);

        return back()->with('ok','Allegato caricato');
    }

    public function download(DpiConsegnaAllegato $allegato)
    {
        return Storage::disk('private')->download($allegato->path, $allegato->nome_file ?? null);
    }
}
