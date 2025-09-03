@extends('layouts.app')

@section('content')
<div class="container py-3">
  <div class="d-flex align-items-center mb-3">
    <h2 class="mb-0">Modelli dinamici (v2)</h2>
    <a href="{{ route('modelli_dinamici.create') }}" class="btn btn-primary ms-auto">+ Nuovo</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-sm align-middle">
      <thead>
        <tr>
          <th>#</th><th>Nome</th><th>Modulo</th><th>Tipologia</th><th>Stato</th><th>Versione</th><th>Azioni</th>
        </tr>
      </thead>
      <tbody>
        @forelse($modelli as $m)
          <tr>
            <td>{{ $m->id }}</td>
            <td>{{ $m->nome }}</td>
            <td>{{ $m->modulo }}</td>
            <td>{{ $m->tipologia_id ?? '—' }}</td>
            <td><span class="badge bg-{{ $m->stato === 'pubblicato' ? 'success':'secondary' }}">{{ $m->stato }}</span></td>
            <td>{{ $m->version }}</td>
            <td class="text-nowrap">
              <a class="btn btn-sm btn-outline-primary" href="{{ route('modelli_dinamici.edit',$m) }}">Builder</a>
              <form action="{{ route('modelli_dinamici.destroy',$m) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminare il modello?')">
                @csrf @method('DELETE')
                <button class="btn btn-sm btn-outline-danger">Elimina</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-muted">Nessun modello.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>
</div>
@endsection


