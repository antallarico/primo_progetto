@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Usi — {{ $prodotto->nome_commerciale }}</h1>

  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <div class="card mb-3">
    <div class="card-body">
      <form method="POST" action="{{ route('chimica.prodotti.usi.store', $prodotto) }}">
        @csrf
        <div class="row">
          <div class="col-md-3 mb-3">
            <label class="form-label">Area</label>
            <input name="area" class="form-control" value="{{ old('area') }}">
          </div>

          <div class="col-md-4 mb-3">
            <label class="form-label">Attrezzatura</label>
            @if($attrezzature->count())
              <select name="attrezzatura_id" class="form-select">
                <option value="">—</option>
                @foreach($attrezzature as $a)
                  <option value="{{ $a->id }}" @selected(old('attrezzatura_id')==$a->id)>{{ $a->nome }}</option>
                @endforeach
              </select>
            @else
              <input class="form-control" value="—" disabled>
              <div class="form-text">Nessun elenco attrezzature disponibile.</div>
            @endif
          </div>

          <div class="col-md-5 mb-3">
            <label class="form-label">Processo/Operazione</label>
            <input name="processo" class="form-control" value="{{ old('processo') }}">
          </div>
        </div>

        <div class="row">
          <div class="col-md-3 mb-3">
            <label class="form-label">Consumo medio</label>
            <input type="number" step="0.01" min="0" name="consumo_medio" class="form-control" value="{{ old('consumo_medio') }}">
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Unità</label>
            <select name="unita" class="form-select">
              <option value="">—</option>
              @foreach(['L','mL','kg','g','pz'] as $u)
                <option value="{{ $u }}" @selected(old('unita')===$u)>{{ $u }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Note</label>
            <input name="note" class="form-control" value="{{ old('note') }}">
          </div>
        </div>

        <div class="d-flex gap-2">
          <a href="{{ route('chimica.prodotti.index') }}" class="btn btn-light">Indietro</a>
          <button class="btn btn-primary">Aggiungi uso</button>
        </div>
      </form>
    </div>
  </div>

  <h5>Elenco usi</h5>
  <table class="table table-striped">
    <thead>
      <tr>
        <th>Area</th><th>Attrezzatura</th><th>Processo</th><th>Consumo</th><th>Note</th><th class="text-end">Azioni</th>
      </tr>
    </thead>
    <tbody>
      @forelse($usi as $u)
        <tr>
          <td>{{ $u->area ?? '—' }}</td>
          <td>{{ $u->attrezzatura->nome ?? '—' }}</td>
          <td>{{ $u->processo ?? '—' }}</td>
          <td>
            @if($u->consumo_medio) {{ rtrim(rtrim(number_format($u->consumo_medio, 2, ',', ''), '0'), ',') }} {{ $u->unita }} @else — @endif
          </td>
          <td>{{ $u->note ?? '—' }}</td>
          <td class="text-end">
			<a class="btn btn-sm btn-secondary" href="{{ route('chimica.prodotti.usi.edit', [$prodotto, $u]) }}">Modifica</a>
            <form method="POST" action="{{ route('chimica.prodotti.usi.destroy', [$prodotto, $u]) }}" class="d-inline" onsubmit="return confirm('Eliminare questo uso?')">
              @csrf @method('DELETE')
              <button class="btn btn-sm btn-danger">Elimina</button>
            </form>
          </td>
        </tr>
      @empty
        <tr><td colspan="6" class="text-muted">Nessun uso registrato.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>
@endsection


