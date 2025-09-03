{{-- Select modello (filtrato lato server) --}}
<div class="mb-3">
  <label class="form-label">Modello scheda</label>
  <select name="modello_id" id="modello_id" class="form-select" required>
    <option value="">-- seleziona --</option>
    @foreach($modelli as $m)
      <option value="{{ $m->id }}" {{ (string)old('modello_id', $modello->id ?? '') === (string)$m->id ? 'selected' : '' }}>
        {{ $m->nome }}
      </option>
    @endforeach
  </select>
</div>

{{-- Form dinamico (mount-by-select) --}}
<x-dynform.mount
  :values="$compilazione->payload_json ?? []"
  :selectSelector="'#modello_id'"
  :base="url('modelli-dinamici')"
  mode="embed"
/>

<div class="mt-3 d-flex gap-2">
  <button class="btn btn-success">💾 Salva</button>
  <a href="{{ route('attrezzature.index') }}" class="btn btn-secondary">Annulla</a>
</div>
