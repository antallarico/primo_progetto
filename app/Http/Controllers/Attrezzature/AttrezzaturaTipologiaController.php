<?php

namespace App\Http\Controllers\Attrezzature;

use App\Http\Controllers\Controller;
use App\Models\AttrezzaturaTipologia;
use Illuminate\Http\Request;

class AttrezzaturaTipologiaController extends Controller
{
    public function index()
    {
        $tipologie = AttrezzaturaTipologia::all();
        return view('attrezzature.tipologie.index', compact('tipologie'));
    }

    public function create()
    {
        return view('attrezzature.tipologie.create');
    }

    public function store(Request $request)
    {
        AttrezzaturaTipologia::create($request->all());
        return redirect()->route('attrezzature.tipologie.index');
    }

    public function edit(AttrezzaturaTipologia $tipologia)
    {
        return view('attrezzature.tipologie.edit', compact('tipologia'));
    }

    public function update(Request $request, AttrezzaturaTipologia $tipologia)
    {
        $tipologia->update($request->all());
        return redirect()->route('attrezzature.tipologie.index');
    }

    public function destroy(AttrezzaturaTipologia $tipologia)
    {
        $tipologia->delete();
        return redirect()->route('attrezzature.tipologie.index');
    }
}
