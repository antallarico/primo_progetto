@php
  // Valore da mostrare nella textarea JSON (edit: array → json string)
  $psd = old('politica_scadenza_default');
  if ($psd === null && isset($tipo) && $tipo->politica_scadenza_default) {
    $psd = json_encode($tipo->politica_scadenza_default, JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
  }
@endphp

<div class="mb-3">
  <label class="form-label">Nome *</label>
  <input name="nome" class="form-control" required value="{{ old('nome', $tipo->nome ?? '') }}">
</div>

<div class="mb-3">
  <label class="form-label">Categoria</label>
  <select name="categoria" class="form-select">
    <option value="">-</option>
    @foreach(['I','II','III'] as $c)
      <option value="{{ $c }}" @selected(old('categoria', $tipo->categoria ?? '') === $c)>{{ $c }}</option>
    @endforeach
  </select>
</div>

<div class="mb-3">
  <label class="form-label">Norma EN</label>
  <input name="norma_en" class="form-control" value="{{ old('norma_en', $tipo->norma_en ?? '') }}">
</div>

<div class="mb-3">
  <label class="form-label">Rischi coperti</label>
  <textarea name="rischi_coperti" class="form-control">{{ old('rischi_coperti', $tipo->rischi_coperti ?? '') }}</textarea>
</div>

<div class="mb-3">
  <label class="form-label">Politica scadenza (JSON)</label>
  <textarea name="politica_scadenza_default" class="form-control" rows="3"
    placeholder='{"base":"mesi_dal_primo_utilizzo","valore":24}'
  >{{ $psd }}</textarea>
  <div class="form-text">
    Esempi: {"base":"mesi_dalla_consegna","valore":36} — {"base":"nessuna_scadenza"}
  </div>
</div>

<div class="mb-3">
  <label class="form-label">Note</label>
  <textarea name="note" class="form-control">{{ old('note', $tipo->note ?? '') }}</textarea>
</div>

{{-- Importante: hidden per gestire il caso di checkbox deselezionata --}}
<input type="hidden" name="attivo" value="0">
<div class="form-check mb-3">
  <input type="checkbox" class="form-check-input" id="attivo" name="attivo" value="1"
         @checked(old('attivo', $tipo->attivo ?? true))>
  <label for="attivo" class="form-check-label">Attivo</label>
</div>


