@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Nuovo Articolo DPI</h1>
  <form method="POST" action="{{ route('dpi.articoli.store') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label">Tipo *</label>
      <select name="tipo_id" class="form-select" required>
        <option value="">-- seleziona --</option>
        @foreach($tipi as $t)
          <option value="{{ $t->id }}">{{ $t->nome }}</option>
        @endforeach
      </select>
    </div>
    <div class="row">
      <div class="col-md-4 mb-3"><label class="form-label">Marca *</label><input name="marca" class="form-control" required></div>
      <div class="col-md-4 mb-3"><label class="form-label">Modello *</label><input name="modello" class="form-control" required></div>
      <div class="col-md-4 mb-3"><label class="form-label">Taglia</label><input name="taglia" class="form-control"></div>
    </div>
    <div class="row">
      <div class="col-md-4 mb-3"><label class="form-label">Cod. Fornitore</label><input name="codice_fornitore" class="form-control"></div>
      <div class="col-md-4 mb-3"><label class="form-label">EAN/SKU</label><input name="ean_sku" class="form-control"></div>
      <div class="col-md-4 mb-3"><label class="form-label">Quantità disponibile *</label><input type="number" min="0" name="quantita_disponibile" class="form-control" required value="0"></div>
    </div>
    <div class="row">
      <div class="col-md-4 mb-3"><label class="form-label">Validità (mesi - fabbricante)</label><input type="number" min="1" max="120" name="validita_mesi_default" class="form-control"></div>
      <div class="col-md-8 mb-3"><label class="form-label">Note</label><input name="note" class="form-control"></div>
    </div>
    <div class="form-check mb-3"><input type="checkbox" class="form-check-input" name="attivo" value="1" checked> <label class="form-check-label">Attivo</label></div>
    <button class="btn btn-primary">Salva</button>
  </form>
</div>
@endsection


