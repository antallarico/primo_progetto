@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Modifica Attrezzatura</h1>

    <form action="{{ route('attrezzature.update', $attrezzatura) }}" method="POST" id="form-attrezzatura">
        @csrf
        @method('PUT')

        @include('attrezzature._form', [
            'attrezzatura' => $attrezzatura,
            'categorie' => $categorie,
            'padri' => $padri,
            'tipologie' => $tipologie,
            'modelli' => $modelli,
			'modello' => $modello,
            'compilazione' => $compilazione ?? null,  {{-- prefill se esiste --}}
        ])

        <div class="mt-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Aggiorna</button>
            <a href="{{ route('attrezzature.index') }}" class="btn btn-secondary">Annulla</a>
        </div>
    </form>
</div>
@endsection
