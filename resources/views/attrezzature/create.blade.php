@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Nuova Attrezzatura</h1>

    <form action="{{ route('attrezzature.store') }}" method="POST" id="form-attrezzatura">
        @csrf

        @include('attrezzature._form', [
            'attrezzatura' => null,
            'categorie' => $categorie,
            'padri' => $padri,
            'tipologie' => $tipologie,
            'modelli' => $modelli,
            'compilazione' => null,   {{-- nessun prefill in create --}}
        ])

        <div class="mt-3 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Salva</button>
            <a href="{{ route('attrezzature.index') }}" class="btn btn-secondary">Annulla</a>
        </div>
    </form>
</div>
@endsection
