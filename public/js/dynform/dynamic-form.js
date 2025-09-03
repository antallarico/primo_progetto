// public/js/dynamic-form-v2.js
(function () {
  // --- utils ---------------------------------------------------------------
  function htmlesc(v) {
    if (v === null || v === undefined) return '';
    return String(v)
      .replace(/&/g, '&amp;')
      .replace(/</g, '&lt;')
      .replace(/>/g, '&gt;')
      .replace(/"/g, '&quot;');
  }

  function safeParse(json, fallback) {
    try { return JSON.parse(json); } catch { return fallback; }
  }

  function normType(type) {
    return String(type || 'text').toLowerCase().replace(/[\s-]/g, '_');
  }

  function normOptions(field) {
    const raw = Array.isArray(field.options) ? field.options : [];
    return raw.map(opt => {
      if (opt && typeof opt === 'object') {
        const v = opt.value ?? opt.val ?? '';
        const l = opt.label ?? opt.text ?? v;
        return { value: String(v), label: String(l) };
      }
      const s = String(opt ?? '');
      return { value: s, label: s };
    });
  }

  function normDateTimeVal(val) {
    const s = String(val ?? '').trim();
    if (!s) return '';
    if (s.includes('T')) return s;
    return s.replace(' ', 'T'); // "2025-08-21 10:30" -> "2025-08-21T10:30"
  }

  // --- input factory -------------------------------------------------------
  function inputFor(field, value) {
    const key     = field.key || field.label || '';
    const t       = normType(field.type || 'text');
    const isMulti = (t === 'multi_select');
    const name    = isMulti ? `payload[${key}][]` : `payload[${key}]`;
    const label   = htmlesc(field.label || key);
    const v       = (value !== undefined && value !== null) ? value : (field.default ?? '');
    const vld     = field.validations || {};
    const req     = field.required ? 'required' : '';

    const attrs = [];
    if (vld.min !== undefined)   attrs.push(`min="${htmlesc(vld.min)}"`);
    if (vld.max !== undefined)   attrs.push(`max="${htmlesc(vld.max)}"`);
    if (vld.step !== undefined)  attrs.push(`step="${htmlesc(vld.step)}"`);
    if (vld.pattern)             attrs.push(`pattern="${htmlesc(vld.pattern)}"`);

    let controlHtml = '';
    let wrapWithUnit = true;

    switch (t) {
      case 'textarea': {
        controlHtml = `<textarea class="form-control" name="${htmlesc(name)}" ${req}>${htmlesc(v)}</textarea>`;
        break;
      }

      case 'select': {
        const options = normOptions(field);
        const current = String(v);
        const optionsHtml = options.map(o => {
          const sel = current === o.value ? 'selected' : '';
          return `<option value="${htmlesc(o.value)}" ${sel}>${htmlesc(o.label)}</option>`;
        }).join('');
        controlHtml = `<select class="form-select" name="${htmlesc(name)}" ${req}>${optionsHtml}</select>`;
        break;
      }

      case 'multi_select': {
        const options = normOptions(field);
        const selVals = Array.isArray(v) ? v.map(String) : (v ? String(v).split(',').map(s=>s.trim()) : []);
        const optionsHtml = options.map(o => {
          const sel = selVals.includes(o.value) ? 'selected' : '';
          return `<option value="${htmlesc(o.value)}" ${sel}>${htmlesc(o.label)}</option>`;
        }).join('');
        controlHtml = `<select multiple class="form-select" name="${htmlesc(name)}" ${req}>${optionsHtml}</select>`;
        break;
      }

      case 'radio': {
        const options = normOptions(field);
        const current = String(v);
        wrapWithUnit = false;
        controlHtml = options.map((o, idx) => {
          const checked = current === o.value ? 'checked' : '';
          const id = `f_${htmlesc(key)}_${idx}`;
          return `
            <div class="form-check">
              <input class="form-check-input" type="radio" id="${id}" name="${htmlesc(name)}" value="${htmlesc(o.value)}" ${checked} ${req}>
              <label class="form-check-label" for="${id}">${htmlesc(o.label)}</label>
            </div>`;
        }).join('');
        break;
      }

      case 'checkbox': {
        const checked = (v === true || String(v) === '1') ? 'checked' : '';
        wrapWithUnit = false;
        controlHtml = `
          <input type="hidden" name="${htmlesc(name)}" value="0">
          <div class="form-check">
            <input type="checkbox" class="form-check-input" name="${htmlesc(name)}" value="1" ${checked} ${req}>
            <label class="form-check-label">${label}</label>
          </div>`;
        return `<div class="mb-3">${controlHtml}</div>`;
      }

      case 'datetime': {
        const type = 'datetime-local';
        const val  = normDateTimeVal(v);
        controlHtml = `<input type="${type}" class="form-control" name="${htmlesc(name)}" value="${htmlesc(val)}" placeholder="YYYY-MM-DDTHH:mm" ${attrs.join(' ')} ${req}>`;
        break;
      }

      case 'time':
      case 'email':
      case 'url':
      case 'tel':
      case 'number':
      case 'date': {
        const type = htmlesc(t);
        controlHtml = `<input type="${type}" class="form-control" name="${htmlesc(name)}" value="${htmlesc(v)}" ${attrs.join(' ')} ${req}>`;
        break;
      }

      case 'range': {
        wrapWithUnit = false;
        const min = (vld.min !== undefined) ? String(vld.min) : '';
        const max = (vld.max !== undefined) ? String(vld.max) : '';
        const val = String(v ?? '');
        const unitBadge = field.unit ? ` <span class="badge bg-light text-dark">${htmlesc(field.unit)}</span>` : '';
        controlHtml = `
          <div class="d-flex align-items-center gap-2">
            ${min ? `<span class="text-muted small">${htmlesc(min)}</span>` : ''}
            <input type="range" class="form-range flex-grow-1" name="${htmlesc(name)}" value="${htmlesc(val)}" ${attrs.join(' ')} ${req}
                   oninput="this.parentNode.querySelector('.rng-val').textContent=this.value">
            ${max ? `<span class="text-muted small">${htmlesc(max)}</span>` : ''}
            <span class="badge bg-secondary"><span class="rng-val">${htmlesc(val || min || '0')}</span>${unitBadge}</span>
          </div>`;
        break;
      }

      default: { // text e fallback
        const type = htmlesc(t || 'text');
        controlHtml = `<input type="${type}" class="form-control" name="${htmlesc(name)}" value="${htmlesc(v)}" ${attrs.join(' ')} ${req}>`;
      }
    }

    const unit = (wrapWithUnit && field.unit) ? `<span class="input-group-text">${htmlesc(field.unit)}</span>` : '';
    const control = unit ? `<div class="input-group">${controlHtml}${unit}</div>` : controlHtml;
    const help = field.help ? `<div class="form-text">${htmlesc(field.help)}</div>` : '';

    return `<div class="mb-3">
              <label class="form-label">${label}</label>
              ${control}
              ${help}
            </div>`;
  }

  // --- renderer core -------------------------------------------------------
  function renderInto(root, schema, layout, values, mode) {
    if (!root) return;

    const fields = Array.isArray(schema?.fields) ? schema.fields : [];
    const fieldMap = {};
    fields.forEach(f => { fieldMap[f.key || f.label] = f; });

    // pulisci root e ricostruisci
    root.innerHTML = '';
    if (mode === 'full') root.classList.add('container', 'py-3');

    // Nessun campo → messaggio chiaro
    if (fields.length === 0) {
      root.insertAdjacentHTML('beforeend',
        '<div class="alert alert-warning mb-0">Il modello selezionato non ha campi definiti.</div>');
      root.dispatchEvent(new CustomEvent('dynform:rendered', { detail: { schema, layout, values, mode } }));
      return;
    }

    // Fallback: nessun layout o sections vuote → lista verticale
    if (!layout || !Array.isArray(layout.sections) || layout.sections.length === 0) {
      fields.forEach(f => {
        root.insertAdjacentHTML('beforeend', inputFor(f, values?.[f.key]));
      });
      root.dispatchEvent(new CustomEvent('dynform:rendered', { detail: { schema, layout, values, mode } }));
      return;
    }

    // Con layout a sezioni/righe/colonne (12-col grid)
    layout.sections
      .slice()
      .sort((a, b) => (a.order || 0) - (b.order || 0))
      .forEach(section => {
        if (section.label) {
          root.insertAdjacentHTML('beforeend', `<h5 class="mt-3 mb-2">${htmlesc(section.label)}</h5>`);
        }

        (section.rows || []).forEach(row => {
          const rowEl = document.createElement('div');
          rowEl.className = 'row g-3';

          (row.fields || []).forEach(cell => {
            const key = cell.key;
            const f = fieldMap[key];
            if (!f) return;

            const col = Math.min(Math.max(parseInt(cell.col || 12, 10), 1), 12);
            const colEl = document.createElement('div');
            colEl.className = `col-md-${col}`;
            colEl.insertAdjacentHTML('afterbegin', inputFor(f, values?.[key]));
            rowEl.appendChild(colEl);
          });

          root.appendChild(rowEl);
        });
      });

    // evento post-render
    root.dispatchEvent(new CustomEvent('dynform:rendered', { detail: { schema, layout, values, mode } }));
  }

  // --- bootstrap DOMContentLoaded -----------------------------------------
  document.addEventListener('DOMContentLoaded', () => {
    const root = document.getElementById('dynform-v2');
    if (!root) return;

    const schema = safeParse(root.dataset.schema || '{}', {});
    const layout = safeParse(root.dataset.layout || 'null', null);
    const values = safeParse(root.dataset.values || '{}', {});
    const mode   = root.dataset.mode || 'embed';

    renderInto(root, schema, layout, values, mode);
  });

  // === DynForm namespace export (non distruttivo) ============================
  window.DynForm = window.DynForm || {};
  const DF = window.DynForm;

  // util
  DF.utils = Object.assign(DF.utils || {}, {
    htmlesc, safeParse, normType, normOptions, normDateTimeVal,
  });

  // api principali
  if (typeof DF.renderInto !== 'function') DF.renderInto = renderInto;

  if (typeof DF.mount !== 'function') {
    DF.mount = function(schema, layout, values, mode = 'embed') {
      const root = document.getElementById('dynform-v2');
      if (!root) return;
      renderInto(root, schema || {fields:[]}, layout || null, values || {}, mode);
    };
  }

  // retrocompat con nomi globali usati altrove
  if (typeof window.mountDynFormV2 !== 'function') window.mountDynFormV2 = DF.mount;
  if (typeof window.renderDynamicFormV2 !== 'function') {
    window.renderDynamicFormV2 = function(el){
      if (!el) return;
      const sc = safeParse(el.dataset.schema || '{}', {});
      const lo = safeParse(el.dataset.layout || 'null', null);
      const vv = safeParse(el.dataset.values || '{}', {});
      const md = el.dataset.mode || 'embed';
      renderInto(el, sc, lo, vv, md);
    };
  }

  // versione per diagnosi
  DF.version = DF.version || '2.2.0';
})();
