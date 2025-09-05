@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Sostanze/Prodotti Chimici</h1>

  <div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" class="d-flex gap-2">
      <input type="text" name="q" class="form-control" placeholder="Cerca nome/fornitore/codice" value="{{ request('q') }}">
      <select name="stato" class="form-select">
        <option value="">Attivi/Non attivi</option>
        <option value="1" @selected(request('stato')==='1')>Attivi</option>
        <option value="0" @selected(request('stato')==='0')>Non attivi</option>
      </select>
      <button class="btn btn-outline-secondary">Filtra</button>
    </form>
    <a href="{{ route('chimica.prodotti.create') }}" class="btn btn-primary">Nuovo Prodotto</a>
  </div>

  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

  <table class="table table-striped">
    <thead><tr>
      <th>Nome</th><th>Tipo</th><th>Fornitore</th><th>UFI</th>
      <th>SDS corrente</th><th></th>	
    </tr></thead>
    <tbody>
    @foreach($prodotti as $p)
      <tr>
        <td>{{ $p->nome_commerciale }}</td>
        <td>{{ ucfirst($p->tipo) }}</td>
        <td>{{ $p->fornitore }}</td>
        <td>{{ $p->ufi }}</td>
        <td>
          @if($p->sdsAttuale)
            <span class="badge bg-success">presente</span>
          @else
            <span class="badge bg-warning text-dark">manca</span>
          @endif
        </td>
        <td class="text-end">
          <a class="btn btn-sm btn-secondary" href="{{ route('chimica.prodotti.edit',$p) }}">Modifica</a>
          <a class="btn btn-sm btn-outline-primary" href="{{ route('chimica.prodotti.sds.index',$p) }}">SDS</a>
          <form action="{{ route('chimica.prodotti.destroy',$p) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminare?')">
            @csrf @method('DELETE')
            <button class="btn btn-sm btn-danger">Elimina</button>
          </form>
        </td>
      </tr>
    @endforeach
    </tbody>
  </table>

  {{ $prodotti->links() }}
</div>
@endsection


