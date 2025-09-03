 // public/js/listing/smartlist.js
(function () {
  const $ = (sel, root = document) => root.querySelector(sel);
  const $$ = (sel, root = document) => Array.from(root.querySelectorAll(sel));
  const debounce = (fn, ms = 300) => {
    let t; return (...a) => { clearTimeout(t); t = setTimeout(() => fn(...a), ms); };
  };

  function normalize(s) {
    return (s ?? '').toString().trim().toLowerCase();
  }

  function initSmartList(root) {
    const filters = {
      q: $('#filter-q', root),
      stato: $('#filter-stato', root),
      categoria: $('#filter-categoria', root),
      tipologia: $('#filter-tipologia', root),
      ce: $('#filter-ce', root),
      reset: $('#filter-reset', root),
      count: $('#results-count', root),
    };

    const table = $('#attrezzature-table', root);
    if (!table) return;
    const tbody = table.tBodies[0];
    if (!tbody) return;

    // Main rows = righe "principali" (non dettagli)
    const isMainRow = (tr) => tr.dataset.row === 'main';
    const rowMap = new Map(); // rowId -> {main, detail}
    $$('#attrezzature-table tbody tr').forEach(tr => {
      const id = tr.dataset.rowId;
      if (!id) return;
      const slot = rowMap.get(id) || {};
      if (isMainRow(tr)) slot.main = tr; else slot.detail = tr;
      rowMap.set(id, slot);
    });

    function applyFilters() {
      const fq = normalize(filters.q?.value || '');
      const fs = filters.stato?.value || '';
      const fc = filters.categoria?.value || '';
      const ft = filters.tipologia?.value || '';
      const fce = filters.ce?.value || '';

      let shown = 0;
      rowMap.forEach(({ main, detail }) => {
        if (!main) return;
        const ds = main.dataset;

        // cumulabili in AND
        let ok = true;
        if (fq)   ok = ok && (ds.text || '').includes(fq);
        if (fs)   ok = ok && (ds.stato || '') === fs;
        if (fc)   ok = ok && (ds.categoriaId || '') === fc;
        if (ft)   ok = ok && (ds.tipologiaId || '') === ft;
        if (fce)  ok = ok && (ds.ce || '') === fce;

        main.style.display = ok ? '' : 'none';
        if (detail) detail.style.display = ok ? '' : 'none';
        if (ok) shown++;
      });

      if (filters.count) {
        filters.count.textContent = `${shown}`;
      }
    }

    const applyFiltersDebounced = debounce(applyFilters, 250);

    // Bind filtri
    [filters.q, filters.stato, filters.categoria, filters.tipologia, filters.ce]
      .forEach(el => el && el.addEventListener(el.tagName === 'INPUT' ? 'input' : 'change', applyFiltersDebounced));

    filters.reset && filters.reset.addEventListener('click', () => {
      if (filters.q) filters.q.value = '';
      if (filters.stato) filters.stato.value = '';
      if (filters.categoria) filters.categoria.value = '';
      if (filters.tipologia) filters.tipologia.value = '';
      if (filters.ce) filters.ce.value = '';
      applyFilters();
    });

    // Sorting
    let sortKey = null, sortDir = 'asc';
    function compare(a, b, type) {
      if (type === 'number') {
        const na = parseFloat(a) || 0, nb = parseFloat(b) || 0;
        return na - nb;
      }
      // string (default)
      return a.localeCompare(b, undefined, { sensitivity: 'base', numeric: true });
    }

    function sortBy(key, type = 'string') {
      sortDir = (sortKey === key && sortDir === 'asc') ? 'desc' : 'asc';
      sortKey = key;

      // prendi solo le MAIN rows visibili
      const mains = $$('#attrezzature-table tbody tr[data-row="main"]').filter(tr => tr.style.display !== 'none');

      mains.sort((r1, r2) => {
        const v1 = normalize(r1.dataset[key]);
        const v2 = normalize(r2.dataset[key]);
        const cmp = compare(v1, v2, type);
        return sortDir === 'asc' ? cmp : -cmp;
      });

      // re-append main + sua detail
      const frag = document.createDocumentFragment();
      mains.forEach(main => {
        const id = main.dataset.rowId;
        const detail = rowMap.get(id)?.detail || null;
        frag.appendChild(main);
        if (detail) frag.appendChild(detail);
      });
      tbody.appendChild(frag);

      // aggiorna caret visuale
      $$('#attrezzature-table thead th').forEach(th => th.removeAttribute('data-sorted'));
      const th = $(`#attrezzature-table thead th[data-sort-key="${key}"]`);
      if (th) th.setAttribute('data-sorted', sortDir);
    }

    // Click sulle intestazioni ordinabili
    $$('#attrezzature-table thead th[data-sort-key]').forEach(th => {
      th.style.cursor = 'pointer';
      th.addEventListener('click', () => sortBy(th.dataset.sortKey, th.dataset.sortType || 'string'));
    });

    // Primo render: filtra (no ordinamento per non sorprendere)
    applyFilters();
  }

  // Auto-boot
  document.addEventListener('DOMContentLoaded', () => {
    $$('#smartlist-root').forEach(initSmartList);
  });
})();
