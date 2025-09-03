@extends('layouts.app')

@section('content')
<div class="container py-3">
  <h2>Scheda Attrezzatura — {{ $attrezzatura->nome }}</h2>

  <form method="POST" action="{{ route('attrezzature.schede.store', $attrezzatura) }}">
    @csrf
    @include('attrezzature.schede._form', [
      'attrezzatura' => $attrezzatura,
      'modelli' => $modelli,
      'modello' => $modello,
      'compilazione' => $compilazione
    ])
  </form>
</div>
@endsection

