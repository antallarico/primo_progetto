@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Modifica Articolo DPI</h1>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul>
    </div>
  @endif
  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif

  <form method="POST" action="{{ route('dpi.articoli.update', $articolo) }}">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">Tipo *</label>
      <select name="tipo_id" class="form-select" required>
        @foreach($tipi as $t)
          <option value="{{ $t->id }}" @selected(old('tipo_id', $articolo->tipo_id)==$t->id)>{{ $t->nome }}</option>
        @endforeach
      </select>
    </div>

    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Marca *</label>
        <input name="marca" class="form-control" required value="{{ old('marca', $articolo->marca) }}">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Modello *</label>
        <input name="modello" class="form-control" required value="{{ old('modello', $articolo->modello) }}">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Taglia</label>
        <input name="taglia" class="form-control" value="{{ old('taglia', $articolo->taglia) }}">
      </div>
    </div>

    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Cod. Fornitore</label>
        <input name="codice_fornitore" class="form-control" value="{{ old('codice_fornitore', $articolo->codice_fornitore) }}">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">EAN/SKU</label>
        <input name="ean_sku" class="form-control" value="{{ old('ean_sku', $articolo->ean_sku) }}">
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Quantità disponibile *</label>
        <input type="number" min="0" name="quantita_disponibile" class="form-control" required
               value="{{ old('quantita_disponibile', $articolo->quantita_disponibile) }}">
      </div>
    </div>

    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Validità (mesi - fabbricante)</label>
        <input type="number" min="1" max="120" name="validita_mesi_default" class="form-control"
               value="{{ old('validita_mesi_default', $articolo->validita_mesi_default) }}">
      </div>
      <div class="col-md-8 mb-3">
        <label class="form-label">Note</label>
        <input name="note" class="form-control" value="{{ old('note', $articolo->note) }}">
      </div>
    </div>

    <div class="form-check mb-3">
      <input type="checkbox" class="form-check-input" name="attivo" value="1" id="attivo"
        @checked(old('attivo', $articolo->attivo))>
      <label for="attivo" class="form-check-label">Attivo</label>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('dpi.articoli.index') }}" class="btn btn-light">Indietro</a>
      <button class="btn btn-primary">Salva modifiche</button>
    </div>
  </form>
</div>
@endsection


