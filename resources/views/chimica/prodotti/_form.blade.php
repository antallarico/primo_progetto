<div class="mb-3">
  <label class="form-label">Nome commerciale *</label>
  <input name="nome_commerciale" class="form-control" required value="{{ old('nome_commerciale', $prodotto->nome_commerciale ?? '') }}">
</div>

<div class="row">
  <div class="col-md-4 mb-3">
    <label class="form-label">Tipo *</label>
    <select name="tipo" class="form-select" required>
      @foreach(['sostanza'=>'Sostanza','miscela'=>'Miscela'] as $k=>$v)
        <option value="{{ $k }}" @selected(old('tipo', $prodotto->tipo ?? '')==$k)>{{ $v }}</option>
      @endforeach
    </select>
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">Codice interno</label>
    <input name="codice_interno" class="form-control" value="{{ old('codice_interno', $prodotto->codice_interno ?? '') }}">
  </div>
  <div class="col-md-4 mb-3">
    <label class="form-label">Codice fornitore</label>
    <input name="codice_fornitore" class="form-control" value="{{ old('codice_fornitore', $prodotto->codice_fornitore ?? '') }}">
  </div>
</div>

<div class="row">
  <div class="col-md-6 mb-3">
    <label class="form-label">Fornitore</label>
    <input name="fornitore" class="form-control" value="{{ old('fornitore', $prodotto->fornitore ?? '') }}">
  </div>
  <div class="col-md-3 mb-3">
    <label class="form-label">UFI</label>
    <input name="ufi" class="form-control" value="{{ old('ufi', $prodotto->ufi ?? '') }}">
  </div>
  <div class="col-md-3 mb-3">
    <label class="form-label d-block">Stato</label>
    <input type="hidden" name="stato" value="0">
    <div class="form-check form-switch">
      <input class="form-check-input" type="checkbox" id="stato" name="stato" value="1" @checked(old('stato', $prodotto->stato ?? true))>
      <label class="form-check-label" for="stato">Attivo</label>
    </div>
  </div>
</div>

<div class="mb-3">
  <label class="form-label">Note</label>
  <textarea name="note" class="form-control">{{ old('note', $prodotto->note ?? '') }}</textarea>
</div>


