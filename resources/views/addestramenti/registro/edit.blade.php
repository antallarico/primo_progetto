@extends('layouts.app')
@section('title','Modifica Addestramento')

@section('content')
<div class="container py-3">
  <h1 class="h5 mb-3">Modifica Addestramento</h1>

  <script>
    window.tipologieAddestramento = @json($tipologieMap);
  </script>

  <form method="POST" action="{{ route('addestramenti.registro.update', $addestramento) }}">
    @csrf @method('PUT')
    @include('addestramenti.registro._form', [
      'addestramento' => $addestramento,
      'values' => $values,
      'attrezzaturaId' => null,
      'tipologiaId' => null,
      'modello' => $modello ?? null,
    ])
	
	<div class="mt-3 d-flex gap-2">
		<button type="submit" class="btn btn-success">💾 Salva</button>
		<a href="{{ route('addestramenti.registro.index') }}" class="btn btn-secondary">Annulla</a>
	</div>
  </form>
</div>
@endsection


