// public/js/dynform/mount-by-select.js
(() => {
  function safeJson(s, fb) { try { return typeof s === 'string' ? JSON.parse(s) : (s ?? fb); } catch { return fb; } }
  function $(sel, root = document) { return root.querySelector(sel); }

  async function fetchJson(url) {
    const res = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
    if (!res.ok) throw new Error('HTTP ' + res.status);
    return res.json();
  }

  function mountInto(container, schema, layout, values, mode, dbg) {
    container.innerHTML = '<div id="dynform-v2" data-mode="' + (mode || 'embed') + '"></div>';
    const DF = window.DynForm || {};
    if (typeof DF.mount === 'function') {
      DF.mount(schema || { fields: [] }, layout || null, values || {}, mode || 'embed');
    } else if (typeof window.mountDynFormV2 === 'function') {
      window.mountDynFormV2(schema || { fields: [] }, layout || null, values || {}, mode || 'embed');
    } else if (typeof DF.renderInto === 'function') {
      const el = container.querySelector('#dynform-v2');
      DF.renderInto(el, schema || { fields: [] }, layout || null, values || {}, mode || 'embed');
    }
    if (dbg) console.log('dynform: montato', { mode, values });
    container.dispatchEvent(new CustomEvent('dynform:mounted', { detail: { schema, layout, values, mode } }));
  }

  function resolveUrl(base, id, tpl) {
    base = String(base || '').replace(/\/$/, '');
    if (tpl) return tpl.replace('__ID__', id);
    return id ? (base + '/' + id + '/json') : null;
  }

  function bindContainer(container) {
    const selectSel   = container.dataset.select   || "[name='modello_id']";
    const base        = container.dataset.base     || "";
    const endpointTpl = container.dataset.endpoint || "";
    const mode        = container.dataset.mode     || "embed";
    const dbg         = (container.dataset.debug === "1" || container.dataset.debug === "true");

    // Valori iniziali passati dal server (EDIT) → usati per il primo mount e se si torna al modello iniziale
    const initialValuesFromData = safeJson(container.dataset.values || "{}", {});
    let values        = initialValuesFromData; // alias per compat
    let mountedId     = null;

    // Se presente nel markup, usiamo data-initial-id; altrimenti lo auto-rileveremo dalla select al bind.
    let initialId     = String(container.dataset.initialId || '');

    if (dbg) console.log('dynform:init container', { selectSel, base, endpointTpl, mode, initialId, initialValues: initialValuesFromData });

    async function load(id, vals) {
      if (!id) { container.innerHTML = ""; mountedId = null; if (dbg) console.log('dynform: id vuoto, pulisco'); return; }
      const url = resolveUrl(base, id, endpointTpl);
      if (dbg) console.log('dynform: fetching', url);
      try {
        const data = await fetchJson(url);
        if (dbg) console.log('dynform: dati ricevuti', { fields: (data.schema?.fields || []).length, sections: (data.layout?.sections || []).length });
        mountInto(container, data.schema, data.layout, vals || {}, mode, dbg);
        mountedId = id;
      } catch (e) {
        console.error('dynform: errore caricamento', e);
        container.innerHTML = '<div class="alert alert-danger">Errore nel caricamento del modello.</div>';
      }
    }

    function bindSelect(sel) {
      if (!sel) { if (dbg) console.warn('dynform: select non trovato', selectSel); return false; }
      if (dbg) console.log('dynform: select trovato', { value: sel.value });

      // iniziale
      const id0 = String(sel.value || '').trim();
      if (!initialId && id0) initialId = id0;   // auto-detect se non specificato nel wrapper
      if (id0) load(id0, initialValuesFromData); // primo mount in EDIT con payload

      // on change
      sel.addEventListener('change', () => {
        const id = String(sel.value || '').trim();
        if (id === mountedId) return;
        // se torni al modello iniziale → rimonta con i valori iniziali dal server
        const vals = (id && initialId && id === initialId) ? initialValuesFromData : {};
        if (dbg) console.log('dynform: change ->', { id, initialId, usingInitialValues: (vals === initialValuesFromData) });
        load(id, vals);
      });
      return true;
    }

    // prova bind immediato
    let sel = $(selectSel);
    if (!bindSelect(sel)) {
      // osserva il DOM finché compare il select
      const observer = new MutationObserver(() => {
        const current = $(selectSel);
        if (current && current !== sel) {
          sel = current;
          if (bindSelect(sel)) observer.disconnect();
        }
      });
      observer.observe(document.body, { childList: true, subtree: true });
    }

    // se il select esiste ma cambia via replace, ribinda
    const rebinder = new MutationObserver(() => {
      const current = $(selectSel);
      if (current && current !== sel) {
        sel = current;
        bindSelect(sel);
      }
    });
    rebinder.observe(document.body, { childList: true, subtree: true });

    // esponi utilità per debug manuale
    container.__dynformLoad = load;
  }

  function init() {
    const containers = document.querySelectorAll('[data-dynform="mount-by-select"]');
    const anyDbg = Array.from(containers).some(c => (c.dataset.debug === "1" || c.dataset.debug === "true"));
    if (anyDbg) console.log('dynform:init found containers:', containers.length);
    containers.forEach(bindContainer);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init, { once: true });
  } else {
    init();
  }

  // funzione globale (debug) per rescan manuale
  window.__dynformRescan = init;
})();
