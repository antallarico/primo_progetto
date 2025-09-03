@extends('layouts.app')
@section('title','Nuova Tipologia Addestramento')

@section('content')
<div class="container">
  <h1 class="h5 mb-3">Nuova Tipologia Addestramento</h1>

  <form method="POST" action="{{ route('addestramenti.tipologie.store') }}">
    @csrf
    @include('addestramenti.tipologie._form', ['tipologia'=>null])
    <button class="btn btn-success">💾 Salva</button>
    <a href="{{ route('addestramenti.tipologie.index') }}" class="btn btn-secondary">Annulla</a>
  </form>
</div>
@endsection

