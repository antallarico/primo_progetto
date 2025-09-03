// public/js/form-builder.js
(() => {
  // Avvio sicuro: parte subito se DOM già pronto, altrimenti attende
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
  } else {
    init();
  }

  function init() {
    // Utilità dal core (se presenti) con fallback
    const DF = window.DynForm || {};
    const esc = (DF.utils && typeof DF.utils.htmlesc === 'function')
      ? DF.utils.htmlesc
      : (v => String(v ?? '').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'));

    // Riferimenti DOM (gli id restano invariati)
    const labelInput     = document.getElementById('campo-label');
    const tipoSelect     = document.getElementById('campo-tipo');
    const opzioniInput   = document.getElementById('campo-opzioni');
    const opzioniWrapper = document.getElementById('campo-opzioni-wrapper');
    const addButton      = document.getElementById('aggiungi-campo');
    const anteprima      = document.getElementById('anteprima-campi');
    const jsonTextarea   = document.getElementById('contenuto-json');
    const campiAggiunti  = document.getElementById('campi-aggiunti');

    // Se qualche nodo chiave non c'è, esci silenziosamente
    if (!labelInput || !tipoSelect || !addButton || !anteprima || !jsonTextarea) {
      // Console solo per debug, non bloccante
      // console.warn('[form-builder] elementi richiesti non trovati');
      return;
    }

    // Nascondi elementi non necessari (comportamento invariato)
    if (jsonTextarea)  jsonTextarea.style.display = 'none';
    if (campiAggiunti) campiAggiunti.style.display = 'none';

    let campi = [];

    // Carica eventuali campi da textarea (edit) — formato legacy: array di oggetti {label,tipo,opzioni?}
    try {
      const parsed = JSON.parse(jsonTextarea.value || '[]');
      if (Array.isArray(parsed)) campi = parsed;
    } catch (_) {
      // JSON vuoto/non valido: ignora
    }

    function aggiornaJSON() {
      // Manteniamo il formato originale
      jsonTextarea.value = JSON.stringify(campi, null, 4);
    }

    function creaBloccoCampo(campo, index) {
      const wrapper = document.createElement('div');
      wrapper.className = 'mb-3 p-3 bg-white border rounded';
      wrapper.dataset.index = String(index);

      const idLabel = `campo_label_${index}`;
      const idTipo  = `campo_tipo_${index}`;
      const idOpt   = `campo_opzioni_${index}`;
      const opzioni = Array.isArray(campo.opzioni) ? campo.opzioni.join(', ') : '';

      wrapper.innerHTML = `
        <div class="row g-2 align-items-end">
          <div class="col-md-4">
            <label class="form-label">Etichetta</label>
            <input type="text" class="form-control" id="${esc(idLabel)}" value="${esc(campo.label || '')}">
          </div>
          <div class="col-md-3">
            <label class="form-label">Tipo</label>
            <select class="form-select" id="${esc(idTipo)}">
              <option value="text"${campo.tipo === 'text' ? ' selected' : ''}>Testo</option>
              <option value="number"${campo.tipo === 'number' ? ' selected' : ''}>Numero</option>
              <option value="select"${campo.tipo === 'select' ? ' selected' : ''}>Select</option>
              <option value="checkbox"${campo.tipo === 'checkbox' ? ' selected' : ''}>Checkbox</option>
              <option value="textarea"${campo.tipo === 'textarea' ? ' selected' : ''}>Textarea</option>
            </select>
          </div>
          <div class="col-md-5 campo-opzioni-wrapper">
            <label class="form-label">Opzioni (solo select)</label>
            <input type="text" class="form-control" id="${esc(idOpt)}" value="${esc(opzioni)}" placeholder="es. A, B, C">
          </div>
        </div>
      `;

      // Visibilità iniziale campo opzioni
      const optWrapper = wrapper.querySelector('.campo-opzioni-wrapper');
      if (campo.tipo !== 'select' && optWrapper) optWrapper.style.display = 'none';

      // Event listeners
      const labelEl = wrapper.querySelector('#' + CSS.escape(idLabel));
      const tipoEl  = wrapper.querySelector('#' + CSS.escape(idTipo));
      const optEl   = wrapper.querySelector('#' + CSS.escape(idOpt));

      if (labelEl) {
        labelEl.addEventListener('input', (e) => {
          campi[index].label = String(e.target.value || '');
          aggiornaJSON();
        });
      }

      if (tipoEl) {
        tipoEl.addEventListener('change', (e) => {
          const nuovoTipo = String(e.target.value || 'text');
          campi[index].tipo = nuovoTipo;

          if (optWrapper) {
            if (nuovoTipo === 'select') {
              optWrapper.style.display = '';
              if (!Array.isArray(campi[index].opzioni)) campi[index].opzioni = [];
            } else {
              optWrapper.style.display = 'none';
              delete campi[index].opzioni;
            }
          }

          aggiornaJSON();
        });
      }

      if (optEl) {
        optEl.addEventListener('input', (e) => {
          const val = String(e.target.value || '').trim();
          campi[index].opzioni = val ? val.split(',').map(x => x.trim()).filter(Boolean) : [];
          aggiornaJSON();
        });
      }

      anteprima.appendChild(wrapper);
    }

    function renderCampi() {
      anteprima.innerHTML = '';
      if (!Array.isArray(campi) || campi.length === 0) {
        const empty = document.createElement('div');
        empty.className = 'text-muted';
        empty.textContent = 'Nessun campo aggiunto';
        anteprima.appendChild(empty);
        return;
      }
      campi.forEach((campo, i) => creaBloccoCampo(campo, i));
    }

    addButton.addEventListener('click', () => {
      const label   = String(labelInput.value || '').trim();
      const tipo    = String(tipoSelect.value || 'text');
      const opzioni = String(opzioniInput.value || '').trim();

      if (!label) return;

      const nuovo = { label, tipo };
      if (tipo === 'select' && opzioni) {
        nuovo.opzioni = opzioni.split(',').map(x => x.trim()).filter(Boolean);
      }

      campi.push(nuovo);

      // pulizia form
      labelInput.value = '';
      tipoSelect.value = 'text';
      opzioniInput.value = '';
      if (opzioniWrapper) opzioniWrapper.style.display = 'none';

      aggiornaJSON();
      renderCampi();
    });

    // toggle opzioni in base al tipo selezionato
    tipoSelect.addEventListener('change', () => {
      if (!opzioniWrapper) return;
      if (tipoSelect.value === 'select') {
        opzioniWrapper.style.display = 'block';
      } else {
        opzioniWrapper.style.display = 'none';
        opzioniInput.value = '';
      }
    });

    // Inizializza visibilità campo opzioni
    if (opzioniWrapper) {
      opzioniWrapper.style.display = (tipoSelect.value === 'select') ? 'block' : 'none';
    }

    // Primo render
    aggiornaJSON();
    renderCampi();
  }
})();
