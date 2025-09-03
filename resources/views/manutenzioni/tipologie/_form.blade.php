<div class="mb-3">
    <label>Nome *</label>
    <input type="text" name="nome" class="form-control" value="{{ old('nome', $tipologia->nome ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Descrizione</label>
    <textarea name="descrizione" class="form-control">{{ old('descrizione', $tipologia->descrizione ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label>Periodicità (mesi)</label>
    <input type="number" name="periodicita_mesi" class="form-control" value="{{ old('periodicita_mesi', $tipologia->periodicita_mesi ?? '') }}">
</div>

<div class="form-check mb-2">
    <input class="form-check-input" type="checkbox" name="obbligatoria" value="1" {{ old('obbligatoria', $tipologia->obbligatoria ?? false) ? 'checked' : '' }}>
    <label class="form-check-label">Obbligatoria</label>
</div>

<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="documentabile" value="1" {{ old('documentabile', $tipologia->documentabile ?? false) ? 'checked' : '' }}>
    <label class="form-check-label">Prevede Verbale o Documento</label>
</div>

{{-- (Opzionale) Info sui modelli collegati, utile in edit --}}
@if(!empty($tipologia) && ($tipologia->modelliDinamici ?? collect())->count())
  <div class="mb-3">
    <label class="form-label">Modelli dinamici associati</label>
    <ul class="small mb-0">
      @foreach($tipologia->modelliDinamici as $m)
        <li>{{ $m->nome }}</li>
      @endforeach
    </ul>
  </div>
@endif

<div class="mb-3 mt-3">
    <label>Note</label>
    <textarea name="note" class="form-control">{{ old('note', $tipologia->note ?? '') }}</textarea>
</div>

<button type="submit" class="btn btn-success">💾 Salva</button>
<a href="{{ route('manutenzioni.tipologie.index') }}" class="btn btn-secondary">Annulla</a>
