@extends('layouts.app')
@section('title','Registro Addestramenti')

@section('content')
<div class="container">
  <h1 class="h5 mb-3">📒 Registro Addestramenti</h1>

  <a href="{{ route('addestramenti.registro.create') }}" class="btn btn-primary btn-sm mb-3">➕ Nuovo Addestramento</a>

  <table class="table table-sm table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th>Data</th>
        <th>Lavoratore</th>
        <th>Tipologia</th>
        <th>Attrezzatura</th>
        <th>Esito</th>
        <th>Scadenza</th>
        <th class="text-end">Azioni</th>
      </tr>
    </thead>
    <tbody>
      @foreach($addestramenti as $r)
      <tr>
        <td>{{ $r->data_addestramento?->format('Y-m-d') }}</td>
        <td>{{ $r->lavoratore->nome ?? '—' }} {{ $r->lavoratore->cognome ?? '' }}</td>
        <td>{{ $r->tipologia->nome ?? '—' }}</td>
        <td>{{ $r->attrezzatura->nome ?? '—' }}</td>
        <td>{{ $r->esito ?? '—' }}</td>
        <td>{{ $r->scade_il?->format('Y-m-d') ?? '—' }}</td>
        <td class="text-end text-nowrap">
          <a href="{{ route('addestramenti.registro.edit', $r) }}" class="btn btn-sm btn-outline-primary">✏️</a>
          <form action="{{ route('addestramenti.registro.destroy', $r) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Eliminare il record del {{ $r->data_addestramento?->format('Y-m-d') }}?');">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-outline-danger">🗑️</button>
          </form>
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>
@endsection


