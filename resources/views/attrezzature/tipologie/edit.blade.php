@extends('layouts.app')

@section('title', 'Modifica Tipologia Attrezzatura')

@section('content')
<div class="container">
    <h2 class="mb-4">✏️ Modifica Tipologia Attrezzatura</h2>

	<form action="{{ route('attrezzature.tipologie.update', ['tipologia' => $tipologia->id]) }}" method="POST">

<!--	<form action="{{ route('attrezzature.tipologie.update', $tipologia) }}" method="POST"> -->
        @csrf
        @method('PUT')

        @include('attrezzature.tipologie.form')

        <button type="submit" class="btn btn-primary">Aggiorna</button>
		<a href="{{ route('attrezzature.tipologie.index') }}" class="btn btn-secondary">Annulla</a>
		
    </form>
</div>
@endsection
