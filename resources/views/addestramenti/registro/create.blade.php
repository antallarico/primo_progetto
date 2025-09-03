@extends('layouts.app')
@section('title','Nuovo Addestramento')

@section('content')
<div class="container py-3">
  <h1 class="h5 mb-3">Nuovo Addestramento</h1>

	<script>
	// mappa tipologia->modello per auto-preselezione (nome usato dal filtro condiviso)
	window.tipologieDefaultModello = @json($tipologieMap);
	</script>


  <form method="POST" action="{{ route('addestramenti.registro.store') }}">
    @csrf
    @include('addestramenti.registro._form', [
      'addestramento' => null,
      'values' => old('payload', []),
      'attrezzaturaId' => $attrezzaturaId ?? null,
      'tipologiaId' => $tipologiaId ?? null,
      'modello' => $modello ?? null,
    ])
	 
	<div class="mt-3 d-flex gap-2">
		<button type="submit" class="btn btn-success">💾 Salva</button>
		<a href="{{ route('addestramenti.registro.index') }}" class="btn btn-secondary">Annulla</a>
	</div>

  </form>
</div>
@endsection

