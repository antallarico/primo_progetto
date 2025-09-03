@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h2>Modifica Tipologia</h2>

    <form method="POST" action="{{ route('manutenzioni.tipologie.update', $tipologia->id) }}">
        @csrf @method('PUT')
        @include('manutenzioni.tipologie._form', ['tipologia' => $tipologia])
    </form>
</div>
@endsection

