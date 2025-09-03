@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h2>Modifica Competenza / Manutentore</h2>

    <form method="POST" action="{{ route('manutenzioni.competenze.update', $competenza->id) }}">
        @csrf @method('PUT')
        @include('manutenzioni.competenze._form', ['competenza' => $competenza])
    </form>
</div>
@endsection
