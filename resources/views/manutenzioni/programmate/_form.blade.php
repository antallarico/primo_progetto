<div class="mb-3">
    <label>Attrezzatura *</label>
    <select name="attrezzatura_id" class="form-select" required>
        <option value="">-- seleziona --</option>
        @foreach ($attrezzature as $a)
            <option value="{{ $a->id }}" {{ old('attrezzatura_id', $manutenzione->attrezzatura_id ?? '') == $a->id ? 'selected' : '' }}>
                {{ $a->nome }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Tipologia *</label>
    <select name="tipologia_id" class="form-select" required>
        <option value="">-- seleziona --</option>
        @foreach ($tipologie as $t)
            <option value="{{ $t->id }}" {{ old('tipologia_id', $manutenzione->tipologia_id ?? '') == $t->id ? 'selected' : '' }}>
                {{ $t->nome }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Competenza</label>
    <select name="competenza_id" class="form-select">
        <option value="">-- nessuna --</option>
        @foreach ($competenze as $c)
            <option value="{{ $c->id }}" {{ old('competenza_id', $manutenzione->competenza_id ?? '') == $c->id ? 'selected' : '' }}>
                {{ $c->nome }} ({{ $c->tipo }})
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label>Frequenza (mesi)</label>
    <input type="number" name="frequenza_mesi" class="form-control" min="1" value="{{ old('frequenza_mesi', $manutenzione->frequenza_mesi ?? '') }}">
</div>

<div class="mb-3">
    <label>Prossima Scadenza</label>
    <input type="date" name="scadenza_prossima" class="form-control" value="{{ old('scadenza_prossima', $manutenzione->scadenza_prossima ?? '') }}">
</div>

<div class="mb-3">
    <label>Note</label>
    <textarea name="note" class="form-control">{{ old('note', $manutenzione->note ?? '') }}</textarea>
</div>

<button type="submit" class="btn btn-success">💾 Salva</button>
<a href="{{ route('manutenzioni.programmate.index') }}" class="btn btn-secondary">Annulla</a>
