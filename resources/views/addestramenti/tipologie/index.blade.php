@extends('layouts.app')
@section('title','Tipologie Addestramento')

@section('content')
<div class="container">
  <h1 class="h5 mb-3">🧩 Tipologie Addestramento</h1>

  <a class="btn btn-primary btn-sm mb-3" href="{{ route('addestramenti.tipologie.create') }}">➕ Nuova tipologia</a>

  <table class="table table-sm table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th>Nome</th>
        <th>Validità</th>
        <th>Modello</th>
        <th>Attiva</th>
        <th class="text-end">Azioni</th>
      </tr>
    </thead>
    <tbody>
      @foreach($tipologie as $t)
      <tr>
        <td>{{ $t->nome }}</td>
        <td>{{ $t->validita_mesi ? $t->validita_mesi.' mesi' : '—' }}</td>
        <td>{{ $t->modello?->nome ?? '—' }}</td>
        <td>{!! $t->attiva ? '<span class="badge bg-success">Sì</span>' : '<span class="badge bg-secondary">No</span>' !!}</td>
        <td class="text-end text-nowrap">
          <a href="{{ route('addestramenti.tipologie.edit', $t) }}" class="btn btn-sm btn-outline-primary">✏️</a>
          <form action="{{ route('addestramenti.tipologie.destroy', $t) }}" method="POST" class="d-inline"
                onsubmit="return confirm('Eliminare «{{ $t->nome }}»?');">
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

