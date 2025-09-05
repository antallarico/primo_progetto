@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Tipi DPI</h1>
  <a href="{{ route('dpi.tipi.create') }}" class="btn btn-primary mb-3">Nuovo Tipo</a>

  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

  <table class="table table-striped">
    <thead><tr><th>Nome</th><th>Categoria</th><th>Norma</th><th>Attivo</th><th></th></tr></thead>
    <tbody>
      @foreach($tipi as $t)
        <tr>
          <td>{{ $t->nome }}</td>
          <td>{{ $t->categoria }}</td>
          <td>{{ $t->norma_en }}</td>
          <td>{{ $t->attivo ? 'Sì' : 'No' }}</td>
          <td class="text-end">
            <a class="btn btn-sm btn-secondary" href="{{ route('dpi.tipi.edit',$t) }}">Modifica</a>
            <form action="{{ route('dpi.tipi.destroy',$t) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminare?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger">Elimina</button>
            </form>
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  {{ $tipi->links() }}
</div>
@endsection


