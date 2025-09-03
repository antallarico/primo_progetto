@extends('layouts.app')

@section('title', 'Modifica Sessione Formativa')

@section('content')
<div class="container">
    <h2 class="mb-4">✏️ Modifica Sessione Formativa</h2>

    <form action="{{ route('formazione.sessioni.update', ['sessione' => $sessione->id]) }}" method="POST">
        @csrf
        @method('PUT')

        @include('formazione.sessioni.form', ['sessione' => $sessione, 'corsi' => $corsi])

        <button type="submit" class="btn btn-primary">Aggiorna</button>
        <a href="{{ route('formazione.sessioni.index') }}" class="btn btn-secondary">Annulla</a>
    </form>
</div>
@endsection
