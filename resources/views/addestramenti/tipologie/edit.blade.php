@extends('layouts.app')
@section('title','Modifica Tipologia Addestramento')

@section('content')
<div class="container">
  <h1 class="h5 mb-3">Modifica Tipologia — {{ $tipologia->nome }}</h1>

  <form method="POST" action="{{ route('addestramenti.tipologie.update', $tipologia) }}">
    @csrf @method('PUT')
    @include('addestramenti.tipologie._form', ['tipologia'=>$tipologia])
    <button class="btn btn-primary">Aggiorna</button>
    <a href="{{ route('addestramenti.tipologie.index') }}" class="btn btn-secondary">Annulla</a>
  </form>
</div>
@endsection

