<div class="mb-3">
    <label for="corso_id" class="form-label">Corso</label>
    <select name="corso_id" class="form-select" required>
        <option value="">-- Seleziona Corso --</option>
        @foreach ($corsi as $corso)
            <option value="{{ $corso->id }}"
                {{ old('corso_id', $sessione->corso_id ?? '') == $corso->id ? 'selected' : '' }}>
                {{ $corso->titolo }} ({{ $corso->codice }})
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="data_sessione" class="form-label">Data</label>
    <input type="date" name="data_sessione" class="form-control"
        value="{{ old('data_sessione', $sessione->data_sessione ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="durata_effettiva" class="form-label">Durata Effettiva (ore)</label>
    <input type="number" name="durata_effettiva" class="form-control" step="0.1" min="0"
        value="{{ old('durata_effettiva', $sessione->durata_effettiva ?? '') }}">
</div>

<div class="mb-3">
    <label for="docente" class="form-label">Docente</label>
    <input type="text" name="docente" class="form-control"
        value="{{ old('docente', $sessione->docente ?? '') }}">
</div>

<div class="mb-3">
    <label for="soggetto_formatore" class="form-label">Soggetto Formatore</label>
    <input type="text" name="soggetto_formatore" class="form-control"
        value="{{ old('soggetto_formatore', $sessione->soggetto_formatore ?? '') }}">
</div>

<div class="mb-3">
    <label for="luogo" class="form-label">Luogo</label>
    <input type="text" name="luogo" class="form-control"
        value="{{ old('luogo', $sessione->luogo ?? '') }}">
</div>

<div class="mb-3">
    <label for="note" class="form-label">Note</label>
    <textarea name="note" class="form-control" rows="3">{{ old('note', $sessione->note ?? '') }}</textarea>
</div>
