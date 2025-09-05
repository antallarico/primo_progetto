<div class="mb-3">
  <label class="form-label">Tipo *</label>
  <select name="tipo_id" class="form-select" required>
    <option value="">-- seleziona --</option>
    @foreach($tipi as $t)
      <option value="{{ $t->id }}" @selected(old('tipo_id', $articolo->tipo_id ?? null) == $t->id)>{{ $t->nome }}</option>
    @endforeach
  </select>
</div>

<div class="row">
  <div class="col-md-4 mb-3">
    <label class="form-label">Marca *</label>
    <input name="marca" class="form-control" required value="{{ old('marca', $articolo->marca ?? '') }}">
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">Modello *</label>
    <input name="modello" class="form-control" required value="{{ old('modello', $articolo->modello ?? '') }}">
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">Taglia</label>
    <input name="taglia" class="form-control" value="{{ old('taglia', $articolo->taglia ?? '') }}">
  </div>
</div>

<div class="row">
  <div class="col-md-4 mb-3">
    <label class="form-label">Cod. Fornitore</label>
    <input name="codice_fornitore" class="form-control" value="{{ old('codice_fornitore', $articolo->codice_fornitore ?? '') }}">
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">EAN/SKU</label>
    <input name="ean_sku" class="form-control" value="{{ old('ean_sku', $articolo->ean_sku ?? '') }}">
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">Quantità disponibile *</label>
    <input type="number" min="0" name="quantita_disponibile" class="form-control" required
           value="{{ old('quantita_disponibile', $articolo->quantita_disponibile ?? 0) }}">
  </div>
</div>

<div class="row">
  <div class="col-md-4 mb-3">
    <label class="form-label">Validità (mesi - fabbricante)</label>
    <input type="number" min="1" max="120" name="validita_mesi_default" class="form-control"
           value="{{ old('validita_mesi_default', $articolo->validita_mesi_default ?? '') }}">
  </div>
  <div class="col-md-8 mb-3">
    <label class="form-label">Note</label>
    <input name="note" class="form-control" value="{{ old('note', $articolo->note ?? '') }}">
  </div>
</div>

<input type="hidden" name="attivo" value="0">
<div class="form-check mb-3">
  <input type="checkbox" class="form-check-input" id="attivo" name="attivo" value="1"
         @checked(old('attivo', $articolo->attivo ?? true))>
  <label for="attivo" class="form-check-label">Attivo</label>
</div>


