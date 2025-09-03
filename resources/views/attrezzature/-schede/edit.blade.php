@extends('layouts.app')

@section('content')
<div class="container py-3">
  <h2>Scheda Attrezzatura — {{ $attrezzatura->nome }}</h2>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <form method="POST" action="{{ route('attrezzature.schede.update', $attrezzatura) }}">
    @csrf @method('PUT')
    @include('attrezzature.schede._form', [
      'attrezzatura' => $attrezzatura,
      'modelli' => $modelli,
      'modello' => $modello,
      'compilazione' => $compilazione
    ])
  </form>
</div>
@endsection

