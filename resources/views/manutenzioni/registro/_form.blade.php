<div class="mb-3">
    <label>Data di esecuzione *</label>
    <input type="date" name="data_esecuzione" class="form-control"
           value="{{ old('data_esecuzione', optional($intervento)->data_esecuzione) }}" required>
</div>

<div class="mb-3">
    <label>Attrezzatura *</label>
    <select name="attrezzatura_id" class="form-select" required>
        <option value="">-- seleziona --</option>
        @foreach ($attrezzature as $a)
            <option value="{{ $a->id }}"
                {{ old('attrezzatura_id', $intervento->attrezzatura_id ?? '') == $a->id ? 'selected' : '' }}>
                {{ $a->nome }}
            </option>
        @endforeach
    </select>
</div>

{{-- Tipologia manutenzione --}}
<div class="mb-3">
    <label for="tipologia_id" class="form-label">Tipologia manutenzione</label>
    <select id="tipologia_id" name="tipologia_id" class="form-select">
        <option value="">-- nessuna --</option>
        @foreach($tipologie as $tipologia)
            <option value="{{ $tipologia->id }}"
                {{ (string)old('tipologia_id', $intervento->tipologia_id ?? '') === (string)$tipologia->id ? 'selected' : '' }}>
                {{ $tipologia->nome }}
            </option>
        @endforeach
    </select>
</div>

{{-- Modello (filtrato inizialmente in base alla tipologia selezionata) --}}
@php
    $tipSel = old('tipologia_id', $intervento->tipologia_id ?? null);
    $modelliFiltrati = $tipSel ? $modelli->where('tipologia_id', $tipSel) : $modelli;
@endphp
<div class="mb-3">
    <label for="modello_id" class="form-label">Modello</label>
    <select id="modello_id" name="modello_id" class="form-select">
        <option value="">-- seleziona --</option>
        @foreach($modelliFiltrati as $m)
            <option value="{{ $m->id }}"
                {{ (string)old('modello_id', $intervento->modello_id ?? '') === (string)$m->id ? 'selected' : '' }}>
                {{ $m->nome }}
            </option>
        @endforeach
    </select>
</div>

{{-- MOUNT POINT del form dinamico tramite componente riusabile --}}
@php
    $payloadPre = (isset($intervento) && $intervento && $intervento->compilazione)
        ? ($intervento->compilazione->payload_json ?? [])
        : [];
@endphp

<x-dynform.mount
  select='[name="modello_id"]'
  :values="$payloadPre"
  :base="url('modelli-dinamici')"
  mode="embed" 
/>

<div class="mb-3">
    <label>Competenza</label>
    <select name="competenza_id" class="form-select">
        <option value="">-- nessuna --</option>
        @foreach ($competenze as $c)
            <option value="{{ $c->id }}"
                {{ old('competenza_id', $intervento->competenza_id ?? '') == $c->id ? 'selected' : '' }}>
                {{ $c->nome }} ({{ $c->tipo }})
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Collegamento a Manutenzione Programmata</label>
    <select name="programmata_id" class="form-select">
        <option value="">-- nessuna --</option>
        @foreach ($programmate as $p)
            <option value="{{ $p->id }}"
                {{ old('programmata_id', $intervento->programmata_id ?? '') == $p->id ? 'selected' : '' }}>
                ID #{{ $p->id }} - {{ $p->attrezzatura->nome ?? '' }} - {{ $p->tipologia->nome ?? '' }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Esito</label>
    <input type="text" name="esito" class="form-control"
           value="{{ old('esito', $intervento->esito ?? '') }}">
</div>

<div class="mb-3">
    <label>Note</label>
    <textarea name="note" class="form-control">{{ old('note', $intervento->note ?? '') }}</textarea>
</div>

<button type="submit" class="btn btn-success">💾 Salva</button>
<a href="{{ route('manutenzioni.registro.index') }}" class="btn btn-secondary">Annulla</a>

{{-- Filtro condiviso (Tipologia → Modello) — usa la variabile JS già presente in create/edit --}}
<x-dynform.filter :modelli="$modelli" tipSelect="#tipologia_id" modelSelect="#modello_id" />

