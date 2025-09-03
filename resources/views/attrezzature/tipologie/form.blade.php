<div class="mb-3">
    <label for="nome" class="form-label">Nome</label>
    <input type="text" name="nome" class="form-control" required value="{{ old('nome', $tipologia->nome ?? '') }}">
</div>

<div class="mb-3">
    <label for="has_scheda" class="form-label">Ha Scheda Specifica?</label>
    <select name="has_scheda" class="form-select">
        <option value="0" {{ old('has_scheda', $tipologia->has_scheda ?? '') == 0 ? 'selected' : '' }}>No</option>
        <option value="1" {{ old('has_scheda', $tipologia->has_scheda ?? '') == 1 ? 'selected' : '' }}>Sì</option>
    </select>
</div>

<div class="mb-3">
    <label for="scheda_tabella" class="form-label">Nome Scheda (tabella modello)</label>
    <input type="text" name="scheda_tabella" class="form-control" value="{{ old('scheda_tabella', $tipologia->scheda_tabella ?? '') }}">
</div>

<div class="mb-3">
    <label for="scheda_view" class="form-label">Percorso Blade della View</label>
    <input type="text" name="scheda_view" class="form-control" value="{{ old('scheda_view', $tipologia->scheda_view ?? '') }}">
</div>
