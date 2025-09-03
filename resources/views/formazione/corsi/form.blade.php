<div class="mb-3">
    <label for="codice" class="form-label">Codice</label>
    <input type="text" name="codice" class="form-control" required value="{{ old('codice', $corso->codice ?? '') }}">
</div>

<div class="mb-3">
    <label for="titolo" class="form-label">Titolo</label>
    <input type="text" name="titolo" class="form-control" required value="{{ old('titolo', $corso->titolo ?? '') }}">
</div>

<div class="mb-3">
    <label for="descrizione" class="form-label">Descrizione</label>
    <textarea name="descrizione" class="form-control">{{ old('descrizione', $corso->descrizione ?? '') }}</textarea>
</div>

<div class="mb-3">
    <label for="durata_ore" class="form-label">Durata (ore)</label>
    <input type="number" name="durata_ore" class="form-control" min="0" value="{{ old('durata_ore', $corso->durata_ore ?? '') }}">
</div>

<div class="mb-3">
    <label for="validita_mesi" class="form-label">Validità (mesi)</label>
    <input type="number" name="validita_mesi" class="form-control" min="0" value="{{ old('validita_mesi', $corso->validita_mesi ?? '') }}">
</div>

<div class="mb-3">
    <label for="normato" class="form-label">Normato</label>
    <select name="normato" class="form-select">
        <option value="0" {{ old('normato', $corso->normato ?? '') == 0 ? 'selected' : '' }}>No</option>
        <option value="1" {{ old('normato', $corso->normato ?? '') == 1 ? 'selected' : '' }}>Sì</option>
    </select>
</div>

<div class="mb-3">
    <label for="obbligatorio" class="form-label">Obbligatorio</label>
    <select name="obbligatorio" class="form-select">
        <option value="0" {{ old('obbligatorio', $corso->obbligatorio ?? '') == 0 ? 'selected' : '' }}>No</option>
        <option value="1" {{ old('obbligatorio', $corso->obbligatorio ?? '') == 1 ? 'selected' : '' }}>Sì</option>
    </select>
</div>
