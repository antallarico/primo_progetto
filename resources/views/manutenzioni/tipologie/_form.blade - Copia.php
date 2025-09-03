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

<div class="form-check mb-2">
    <input class="form-check-input" type="checkbox" name="con_checklist" id="checklistToggle" value="1" {{ old('con_checklist', $tipologia->con_checklist ?? false) ? 'checked' : '' }}>
    <label class="form-check-label">Prevede Checklist</label>
</div>

<div class="form-check mb-3">
    <input class="form-check-input" type="checkbox" name="documentabile" value="1" {{ old('documentabile', $tipologia->documentabile ?? false) ? 'checked' : '' }}>
    <label class="form-check-label">Prevede Verbale o Documento</label>
</div>

<div id="checklistContainer" style="display: {{ old('con_checklist', $tipologia->con_checklist ?? false) ? 'block' : 'none' }};">
    <h5>Checklist</h5>
    <div id="checklistItems">
        @php
            $voci = old('checklist', $tipologia->checklistDinamica->contenuto ?? []);
        @endphp
        @foreach ($voci as $index => $voce)
            <div class="mb-2 border p-2 rounded">
                <input type="text" name="checklist[{{ $index }}][voce]" value="{{ $voce['voce'] ?? '' }}" class="form-control mb-1" placeholder="Descrizione voce">
                <div class="form-check">
                    <input type="hidden" name="checklist[{{ $index }}][obbligatoria]" value="0">
                    <input type="checkbox" name="checklist[{{ $index }}][obbligatoria]" class="form-check-input" value="1" {{ ($voce['obbligatoria'] ?? false) ? 'checked' : '' }}>
                    <label class="form-check-label">Obbligatoria</label>
                </div>
            </div>
        @endforeach
    </div>
    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addChecklistItem()">➕ Aggiungi voce</button>
</div>

<div class="mb-3 mt-3">
    <label>Note</label>
    <textarea name="note" class="form-control">{{ old('note', $tipologia->note ?? '') }}</textarea>
</div>

<button type="submit" class="btn btn-success">💾 Salva</button>
<a href="{{ route('manutenzioni.tipologie.index') }}" class="btn btn-secondary">Annulla</a>

<script>
    document.getElementById('checklistToggle').addEventListener('change', function () {
        document.getElementById('checklistContainer').style.display = this.checked ? 'block' : 'none';
    });

    function addChecklistItem() {
        const index = document.querySelectorAll('#checklistItems > div').length;
        const container = document.getElementById('checklistItems');

        const html = `
            <div class="mb-2 border p-2 rounded">
                <input type="text" name="checklist[${index}][voce]" class="form-control mb-1" placeholder="Descrizione voce">
                <div class="form-check">
                    <input type="hidden" name="checklist[${index}][obbligatoria]" value="0">
                    <input type="checkbox" name="checklist[${index}][obbligatoria]" class="form-check-input" value="1">
                    <label class="form-check-label">Obbligatoria</label>
                </div>
            </div>`;
        container.insertAdjacentHTML('beforeend', html);
    }
</script>
