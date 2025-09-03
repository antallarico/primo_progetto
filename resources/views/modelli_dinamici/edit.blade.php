@extends('layouts.app')

<div class="container">
    <h1>Modifica Modello Dinamico</h1>

    <form method="POST" action="{{ route('modelli_dinamici.update', $modelloDinamico) }}">
        @csrf
        @method('PUT')
        @include('modelli_dinamici._form')
        <button type="submit" class="btn btn-primary">Aggiorna</button>
        <a href="{{ route('modelli_dinamici.index') }}" class="btn btn-secondary">Annulla</a>
    </form>
</div>
