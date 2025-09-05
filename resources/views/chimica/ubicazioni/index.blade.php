@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Ubicazioni — {{ $prodotto->nome_commerciale }}</h1>

  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <div class="card mb-3">
    <div class="card-body">
      <form method="POST" action="{{ route('chimica.prodotti.ubicazioni.store', $prodotto) }}">
        @csrf
        <div class="row">
          <div class="col-md-4 mb-3">
            <label class="form-label">Ubicazione *</label>
            <input name="ubicazione" class="form-control" required value="{{ old('ubicazione') }}">
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Quantità *</label>
            <input type="number" step="0.01" min="0" name="quantita_disponibile" class="form-control" required value="{{ old('quantita_disponibile', 0) }}">
          </div>
          <div class="col-md-2 mb-3">
            <label class="form-label">Unità *</label>
            <select name="unita" class="form-select" required>
              @foreach(['L','mL','kg','g','pz'] as $u)
                <option value="{{ $u }}" @selected(old('unita')===$u)>{{ $u }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Note</label>
            <input name="note" class="form-control" value="{{ old('note') }}">
          </div>
        </div>

        <div class="d-flex gap-2">
          <a href="{{ route('chimica.prodotti.index') }}" class="btn btn-light">Indietro</a>
          <button class="btn btn-primary">Aggiungi ubicazione</button>
        </div>
      </form>
    </div>
  </div>

  <h5>Elenco ubicazioni</h5>
  <table class="table table-striped align-middle">
    <thead>
      <tr>
        <th>Ubicazione</th><th>Quantità</th><th>Unità</th><th>Note</th><th class="text-end">Azioni</th>
      </tr>
    </thead>
    <tbody>
      @forelse($ubicazioni as $u)
        <tr>
          <td>{{ $u->ubicazione }}</td>
          <td>
            <form class="d-flex gap-2" method="POST" action="{{ route('chimica.ubicazioni.update', $u) }}">
              @csrf @method('PUT')
              <input type="number" step="0.01" min="0" name="quantita_disponibile" class="form-control form-control-sm"
                     style="max-width:140px" value="{{ number_format($u->quantita_disponibile, 2, '.', '') }}">
              <input type="text" name="note" class="form-control form-control-sm" placeholder="Note" value="{{ $u->note }}" style="max-width:260px">
              <button class="btn btn-sm btn-outline-primary">Salva</button>
            </form>
          </td>
          <td>{{ $u->unita }}</td>
          <td>{{ $u->note ?? '—' }}</td>
          <td class="text-end">
			<a class="btn btn-sm btn-secondary" href="{{ route('chimica.prodotti.ubicazioni.edit', [$prodotto, $u]) }}">Modifica</a>
            <form method="POST" action="{{ route('chimica.prodotti.ubicazioni.destroy', [$prodotto, $u]) }}" class="d-inline" onsubmit="return confirm('Eliminare questa ubicazione?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger">Elimina</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="5" class="text-muted">Nessuna ubicazione registrata.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection


