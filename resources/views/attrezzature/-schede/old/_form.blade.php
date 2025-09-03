<div class="mb-3">
    <label for="tipologia_id" class="form-label">Tipologia *</label>
    <select name="tipologia_id" id="tipologia_id" class="form-select" required>
        <option value="">-- seleziona --</option>
        @foreach ($tipologie as $t)
            <option value="{{ $t->id }}"
                {{ old('tipologia_id', $scheda->tipologia_id ?? '') == $t->id ? 'selected' : '' }}>
                {{ $t->nome }}
            </option>
        @endforeach
    </select>
</div>

<div class="mb-3">
    <label for="nome" class="form-label">Nome scheda *</label>
    <input type="text" name="nome" id="nome" class="form-control"
           value="{{ old('nome', $scheda->nome ?? '') }}" required>
</div>

<input type="hidden" name="contenuto" id="contenuto" value='@json(old("contenuto", $scheda->contenuto ?? []))'>

<div class="mb-3">
    <label class="form-label">Campi della scheda</label>
    <div id="contenuto-editor"></div>
</div>

<div class="mb-3">
    <button type="button" class="btn btn-sm btn-primary" onclick="aggiungiCampo()">➕ Aggiungi Campo</button>
</div>

<div class="mt-4">
    <button type="submit" class="btn btn-success">💾 Salva</button>
    <a href="{{ route('attrezzature.schede.index') }}" class="btn btn-secondary">Annulla</a>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {
    const contenutoField = document.getElementById('contenuto');
    const editor = document.getElementById('contenuto-editor');

    function renderCampi(data) {
        editor.innerHTML = '';
        data.forEach((campo, index) => {
            const opzioni = campo.opzioni ? campo.opzioni.join(',') : '';
            const div = document.createElement('div');
            div.className = 'border rounded p-3 mb-3 bg-light';

            div.innerHTML = `
                <div class="mb-2">
                    <label>Label *</label>
                    <input type="text" class="form-control" placeholder="Es: Controllo visivo"
                        value="${campo.label || ''}" 
                        oninput="updateCampo(${index}, 'label', this.value)">
                </div>
                <div class="mb-2">
                    <label>Tipo *</label>
                    <select class="form-select" onchange="updateTipo(${index}, this)">
                        <option value="text" ${campo.tipo === 'text' ? 'selected' : ''}>Testo</option>
                        <option value="number" ${campo.tipo === 'number' ? 'selected' : ''}>Numero</option>
                        <option value="checkbox" ${campo.tipo === 'checkbox' ? 'selected' : ''}>Checkbox</option>
                        <option value="select" ${campo.tipo === 'select' ? 'selected' : ''}>Select (menu a tendina)</option>
                    </select>
                </div>
                <div class="mb-2 opzioni-container ${campo.tipo === 'select' ? '' : 'd-none'}">
                    <label>Opzioni (separate da virgola)</label>
                    <input type="text" class="form-control" placeholder="Es: OK,KO,N/A"
                        value="${opzioni}" 
                        oninput="updateCampo(${index}, 'opzioni', this.value)">
                </div>
                <button type="button" class="btn btn-sm btn-danger mt-2" onclick="rimuoviCampo(${index})">🗑️ Rimuovi</button>
            `;

            editor.appendChild(div);
        });

        editor.querySelectorAll('select').forEach((sel, idx) => {
            sel.addEventListener('change', function () {
                const wrapper = sel.closest('.border');
                const optBox = wrapper.querySelector('.opzioni-container');
                if (sel.value === 'select') {
                    optBox.classList.remove('d-none');
                } else {
                    optBox.classList.add('d-none');
                }
            });
        });
    }

    window.updateCampo = function(index, campo, valore) {
        const data = JSON.parse(contenutoField.value || '[]');
        if (campo === 'opzioni') {
            data[index].opzioni = valore.split(',').map(s => s.trim()).filter(s => s);
        } else {
            data[index][campo] = valore;
        }
        contenutoField.value = JSON.stringify(data);
    }

    window.updateTipo = function(index, selectEl) {
        updateCampo(index, 'tipo', selectEl.value);
        const wrapper = selectEl.closest('.border');
        const optBox = wrapper.querySelector('.opzioni-container');
        if (selectEl.value === 'select') {
            optBox.classList.remove('d-none');
        } else {
            optBox.classList.add('d-none');
        }
    }

    window.rimuoviCampo = function(index) {
        const data = JSON.parse(contenutoField.value || '[]');
        data.splice(index, 1);
        contenutoField.value = JSON.stringify(data);
        renderCampi(data);
    }

    window.aggiungiCampo = function() {
        const data = JSON.parse(contenutoField.value || '[]');
        data.push({ label: '', tipo: 'text' });
        contenutoField.value = JSON.stringify(data);
        renderCampi(data);
    }

    try {
        const iniziale = JSON.parse(contenutoField.value || '[]');
        renderCampi(iniziale);
    } catch (e) {
        contenutoField.value = '[]';
        renderCampi([]);
    }
});
</script>
