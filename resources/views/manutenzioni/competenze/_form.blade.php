<div class="mb-3">
    <label>Nome *</label>
    <input type="text" name="nome" class="form-control" value="{{ old('nome', $competenza->nome ?? '') }}" required>
</div>

<div class="mb-3">
    <label>Tipo *</label>
    <select name="tipo" class="form-select" required>
        <option value="interno" {{ old('tipo', $competenza->tipo ?? '') == 'interno' ? 'selected' : '' }}>Interno</option>
        <option value="esterno" {{ old('tipo', $competenza->tipo ?? '') == 'esterno' ? 'selected' : '' }}>Esterno</option>
    </select>
</div>

<div class="mb-3">
    <label>Contatti</label>
    <input type="text" name="contatti" class="form-control" value="{{ old('contatti', $competenza->contatti ?? '') }}">
</div>

<div class="mb-3">
    <label>Abilitazioni / Certificazioni</label>
    <textarea name="abilitazioni" class="form-control">{{ old('abilitazioni', $competenza->abilitazioni ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label>Note</label>
    <textarea name="note" class="form-control">{{ old('note', $competenza->note ?? '') }}</textarea>
</div>

<button type="submit" class="btn btn-success">💾 Salva</button>
<a href="{{ route('manutenzioni.competenze.index') }}" class="btn btn-secondary">Annulla</a>
