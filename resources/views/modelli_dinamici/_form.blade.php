<div class="mb-3">
    <label for="nome" class="form-label">Nome</label>
    <input type="text" name="nome" class="form-control" value="{{ old('nome', $modelloDinamico->nome ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="modulo" class="form-label">Modulo</label>
    <input type="text" name="modulo" class="form-control" value="{{ old('modulo', $modelloDinamico->modulo ?? '') }}" required>
</div>

<div class="mb-3">
    <label for="tipologia_id" class="form-label">Tipologia ID (opzionale)</label>
    <input type="number" name="tipologia_id" class="form-control" value="{{ old('tipologia_id', $modelloDinamico->tipologia_id ?? '') }}">
</div>

<hr>
<h5>Costruttore del Modello</h5>

<div class="mb-4" id="form-builder">
    <div class="row g-3 align-items-end">
        <div class="col-md-4">
            <label class="form-label">Etichetta</label>
            <input type="text" id="campo-label" class="form-control">
        </div>

        <div class="col-md-3">
            <label class="form-label">Tipo</label>
            <select id="campo-tipo" class="form-select">
                <option value="text">Testo</option>
                <option value="number">Numero</option>
                <option value="select">Select</option>
                <option value="checkbox">Checkbox</option>
                <option value="textarea">Textarea</option>
            </select>
        </div>

        <div class="col-md-5" id="campo-opzioni-wrapper" style="display: none;">
            <label class="form-label">Opzioni (solo per select, separate da virgola)</label>
            <input type="text" id="campo-opzioni" class="form-control">
        </div>

        <div class="col-12">
            <button type="button" id="aggiungi-campo" class="btn btn-primary">Aggiungi Campo</button>
        </div>
    </div>
</div>

<ul class="d-none" id="campi-aggiunti"></ul>

<textarea name="contenuto" id="contenuto-json" class="d-none" readonly required>
    {{ old('contenuto', json_encode($modelloDinamico->contenuto ?? [], JSON_PRETTY_PRINT)) }}
</textarea>

<div class="mt-4">
    <label class="form-label">Anteprima campi</label>
    <div id="anteprima-campi" class="p-3 border rounded bg-light"></div>
</div>

@push('scripts')
<script src="{{ asset('js/form-builder.js') }}"></script>
<script src="{{ asset('js/dynamic-preview.js') }}"></script>
@endpush
