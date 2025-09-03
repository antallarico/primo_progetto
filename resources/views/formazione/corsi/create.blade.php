@extends('layouts.app')

@section('title', 'Nuovo Corso di Formazione')

@section('content')
<div class="container">
    <h2 class="mb-4">➕ Nuovo Corso di Formazione</h2>

    <form action="{{ route('formazione.corsi.store') }}" method="POST">
        @csrf

        @include('formazione.corsi.form')

        <button type="submit" class="btn btn-success">Salva</button>
        <a href="{{ route('formazione.corsi.index') }}" class="btn btn-secondary">Annulla</a>
    </form>
</div>
@endsection
