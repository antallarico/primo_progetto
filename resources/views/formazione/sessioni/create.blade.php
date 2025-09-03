@extends('layouts.app')

@section('title', 'Nuova Sessione Formativa')

@section('content')
<div class="container">
    <h2 class="mb-4">➕ Nuova Sessione Formativa</h2>

    <form action="{{ route('formazione.sessioni.store') }}" method="POST">
        @csrf

        @include('formazione.sessioni.form')

        <button type="submit" class="btn btn-primary">Salva</button>
        <a href="{{ route('formazione.sessioni.index') }}" class="btn btn-secondary">Annulla</a>
    </form>
</div>
@endsection
