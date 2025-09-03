(function () {
  function initNode(node) {
    const tipSel = document.querySelector(node.getAttribute('data-tip-select') || '#tipologia_id');
    const modSel = document.querySelector(node.getAttribute('data-model-select') || '#modello_id');
    const debug = !!node.getAttribute('data-debug');
    if (!tipSel || !modSel) return;

    // Modelli dal global; se mancano, lasciamo le options server-side
    const list = Array.isArray(window.modelliDinamici) ? window.modelliDinamici : null;

    // Mappa opzionale tipologia -> modello_id per auto-preselezione (se vuoi abilitarla nelle pagine)
    const defaultMap = Array.isArray(window.tipologieDefaultModello) ? window.tipologieDefaultModello : null;
    if (debug) console.debug('[DynForm][Filter] init', {
      tipSel, modSel, modelliCount: list ? list.length : 'N/A',
      hasDefaultMap: !!defaultMap
    });

    function rebuild() {
      if (!list) return; // fallback
      const tip = tipSel.value || '';
      const current = modSel.value;

      const filtered = list.filter(m => !tip || String(m.tipologia_id) === String(tip));

      modSel.innerHTML = '<option value="">-- seleziona --</option>';
      filtered.forEach(m => {
        const opt = document.createElement('option');
        opt.value = m.id;
        opt.textContent = m.nome;
        if (String(m.id) === String(current)) opt.selected = true;
        modSel.appendChild(opt);
      });

      if (debug) console.debug('[DynForm][Filter] rebuilt', { tip, filtered: filtered.length, current: modSel.value });
    }

    function maybePreselect() {
      // se non c'è mappa → non facciamo nulla (solo dispatch change per montare/smontare DynForm)
      const tip = tipSel.value || '';
      if (defaultMap) {
        const rec = defaultMap.find(x => String(x.id) === String(tip));
        if (rec && rec.modello_id) {
          const exists = Array.from(modSel.options).some(o => String(o.value) === String(rec.modello_id));
          if (exists) modSel.value = String(rec.modello_id);
        }
      }
      modSel.dispatchEvent(new Event('change', { bubbles: true }));
    }

    tipSel.addEventListener('change', function () {
      modSel.value = '';
      rebuild();
      maybePreselect();
    });

    // primo render
    rebuild();
    maybePreselect();
  }

  function boot() {
    document.querySelectorAll('[data-dynform="filter-tipologia-modello"]').forEach(initNode);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', boot, { once: true });
  } else {
    boot();
  }
})();
