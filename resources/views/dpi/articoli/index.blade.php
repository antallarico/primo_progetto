@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Articoli DPI</h1>
  <div class="d-flex justify-content-between mb-3">
    <form method="GET" class="d-flex gap-2">
      <select name="tipo_id" class="form-select">
        <option value="">Tutti i tipi</option>
        @foreach($tipi as $t)
          <option value="{{ $t->id }}" @selected(request('tipo_id')==$t->id)>{{ $t->nome }}</option>
        @endforeach
      </select>
      <select name="attivo" class="form-select">
        <option value="">Attivi/Non attivi</option>
        <option value="1" @selected(request('attivo')==='1')>Attivi</option>
        <option value="0" @selected(request('attivo')==='0')>Non attivi</option>
      </select>
      <button class="btn btn-outline-secondary">Filtra</button>
    </form>
    <a href="{{ route('dpi.articoli.create') }}" class="btn btn-primary">Nuovo Articolo</a>
  </div>

  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

  <table class="table table-striped">
    <thead><tr>
      <th>Tipo</th><th>Marca/Modello</th><th>Taglia</th><th>Q.ta disp.</th><th>Validità (mesi)</th><th></th>
    </tr></thead>
    <tbody>
    @foreach($articoli as $a)
      <tr>
        <td>{{ $a->tipo->nome ?? '-' }}</td>
        <td>{{ $a->marca }} {{ $a->modello }}</td>
        <td>{{ $a->taglia }}</td>
        <td>{{ $a->quantita_disponibile }}</td>
        <td>{{ $a->validita_mesi_default ?: '-' }}</td>
        <td class="text-end">
          <a href="{{ route('dpi.articoli.edit',$a) }}" class="btn btn-sm btn-secondary">Modifica</a>
          <form action="{{ route('dpi.articoli.destroy',$a) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminare?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger">Elimina</button>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

  {{ $articoli->links() }}
</div>
@endsection


