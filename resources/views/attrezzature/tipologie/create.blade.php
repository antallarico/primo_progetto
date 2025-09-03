@extends('layouts.app')

@section('title', 'Nuova Tipologia Attrezzatura')

@section('content')
<div class="container">
    <h2 class="mb-4">➕ Nuova Tipologia Attrezzatura</h2>

    <form action="{{ route('attrezzature.tipologie.store') }}" method="POST">
        @csrf

        @include('attrezzature.tipologie.form')

        <button type="submit" class="btn btn-success">Salva</button>
        <a href="{{ route('attrezzature.tipologie.index') }}" class="btn btn-secondary">Annulla</a>
    </form>
</div>
@endsection
