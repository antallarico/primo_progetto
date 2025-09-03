@extends('layouts.app')

@section('title', 'Modifica Corso')

@section('content')
<div class="container">
    <h2 class="mb-4">✏️ Modifica Corso</h2>

    <form action="{{ route('formazione.corsi.update', ['corso' => $corso->id]) }}" method="POST">
        @csrf
        @method('PUT')

        @include('formazione.corsi.form')

        <button type="submit" class="btn btn-primary">Aggiorna</button>
        <a href="{{ route('formazione.corsi.index') }}" class="btn btn-secondary">Annulla</a>
    </form>
</div>
@endsection
