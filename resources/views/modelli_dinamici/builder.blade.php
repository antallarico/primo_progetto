@extends('layouts.app')

@section('content')
<div class="container py-3">
  <div class="d-flex align-items-center mb-2">
    <h2 class="mb-0">Builder — {{ $modello->nome }}</h2>
    <a href="{{ route('modelli_dinamici.index') }}" class="btn btn-secondary ms-auto">Torna</a>
  </div>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if($errors->any())
    <div class="alert alert-danger">{!! implode('<br>',$errors->all()) !!}</div>
  @endif

  <form method="POST" action="{{ route('modelli_dinamici.update',$modello) }}" id="builder-form">
    @csrf @method('PUT')
    <div class="row g-3">
      <div class="col-lg-5">

        {{-- Metadati --}}
        <div class="card shadow-sm">
          <div class="card-header">Metadati</div>
          <div class="card-body">
            <div class="mb-2">
              <label class="form-label">Nome *</label>
              <input name="nome" class="form-control" value="{{ old('nome',$modello->nome) }}" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Modulo *</label>
              <input name="modulo" class="form-control" value="{{ old('modulo',$modello->modulo) }}" required>
            </div>
            <div class="mb-2">
              <label class="form-label">Tipologia (ID)</label>
              <input name="tipologia_id" class="form-control" value="{{ old('tipologia_id',$modello->tipologia_id) }}">
            </div>
            <div class="mb-2">
              <label class="form-label">Stato *</label>
              <select name="stato" class="form-select" required>
                <option value="bozza" {{ $modello->stato==='bozza'?'selected':'' }}>Bozza</option>
                <option value="pubblicato" {{ $modello->stato==='pubblicato'?'selected':'' }}>Pubblicato</option>
              </select>
            </div>
          </div>
        </div>

        {{-- CAMPi (UI) --}}
        <div class="card shadow-sm mt-3">
          <div class="card-header d-flex align-items-center">
            Campi (UI)
            <button type="button" id="ui-add-field" class="btn btn-sm btn-outline-secondary ms-auto">+ Campo</button>
          </div>
          <div class="card-body">
            <div id="ui-fields-list" class="list-group"></div>
          </div>
        </div>

        {{-- Editor Campo --}}
        <div class="card shadow-sm mt-3 d-none" id="ui-field-editor">
          <div class="card-header d-flex align-items-center">
            <strong id="ui-field-editor-title">Editor campo</strong>
            <div class="btn-group btn-group-sm ms-auto">
              <button type="button" class="btn btn-success" id="f-save">Salva</button>
              <button type="button" class="btn btn-outline-secondary" id="f-cancel">Annulla</button>
              <button type="button" class="btn btn-outline-danger d-none" id="f-delete">Elimina</button>
            </div>
          </div>
          <div class="card-body">
            <input type="hidden" id="f-index">

            <div class="row g-2">
              <div class="col-6">
                <label class="form-label">Key</label>
                <input id="f-key" class="form-control" placeholder="es. temperatura_motore">
              </div>
              <div class="col-6">
                <label class="form-label">Label</label>
                <input id="f-label" class="form-control" placeholder="Etichetta">
              </div>
              <div class="col-6">
                <label class="form-label">Tipo</label>
                <select id="f-type" class="form-select">
                  <option value="text">text</option>
                  <option value="number">number</option>
                  <option value="date">date</option>
                  <option value="time">time</option>
                  <option value="datetime">datetime</option>
                  <option value="email">email</option>
                  <option value="url">url</option>
                  <option value="tel">tel</option>
                  <option value="textarea">textarea</option>
                  <option value="select">select</option>
                  <option value="multi_select">multi_select</option>
                  <option value="radio">radio</option>
                  <option value="checkbox">checkbox</option>
                  <option value="range">range</option>
                </select>
              </div>
              <div class="col-6">
                <label class="form-label">Required</label>
                <select id="f-required" class="form-select">
                  <option value="false">no</option>
                  <option value="true">sì</option>
                </select>
              </div>
              <div class="col-6">
                <label class="form-label">Unit</label>
                <input id="f-unit" class="form-control" placeholder="es. °C, bar">
              </div>
              <div class="col-6">
                <label class="form-label">Help</label>
                <input id="f-help" class="form-control" placeholder="testo di aiuto">
              </div>
            </div>

            <div class="mt-3" id="wrap-options" style="display:none;">
              <label class="form-label">Opzioni (per select / radio / multi_select)</label>
              <input id="f-options" class="form-control" placeholder="A, B, C">
            </div>

            <div class="mt-3">
              <div class="row g-2">
                <div class="col-4">
                  <label class="form-label">Min</label>
                  <input id="f-min" type="number" class="form-control" placeholder="min">
                </div>
                <div class="col-4">
                  <label class="form-label">Max</label>
                  <input id="f-max" type="number" class="form-control" placeholder="max">
                </div>
                <div class="col-4">
                  <label class="form-label">Step</label>
                  <input id="f-step" type="number" class="form-control" placeholder="step">
                </div>
              </div>
            </div>

            <div class="mt-3" id="wrap-pattern" style="display:none;">
              <label class="form-label">Pattern (regex per email/url/tel)</label>
              <input id="f-pattern" class="form-control" placeholder="es. ^[0-9]{10}$">
            </div>
          </div>
        </div>

        {{-- Layout (UI) --}}
        <div class="card shadow-sm mt-3">
          <div class="card-header d-flex align-items-center">
            Layout (UI)
            <button type="button" id="ui-add-section" class="btn btn-sm btn-outline-secondary ms-auto">+ Sezione</button>
          </div>
          <div class="card-body">
            <div id="ui-sections"></div>
          </div>
        </div>

        {{-- Avanzato (JSON) --}}
        <div class="card shadow-sm mt-3">
          <div class="card-header">Avanzato (JSON)</div>
          <div class="card-body">
            <div class="mb-3">
              <label class="form-label">Schema (fields)</label>
              <textarea name="schema_json" id="schema_json" class="form-control" rows="10">{{ json_encode($schema, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</textarea>
            </div>
            <div class="mb-0">
              <label class="form-label">Layout (sections/rows)</label>
              <textarea name="layout_json" id="layout_json" class="form-control" rows="10">{{ json_encode($layout, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE) }}</textarea>
            </div>
          </div>
        </div>

        <div class="mt-3 d-flex">
          <button class="btn btn-primary ms-auto">💾 Salva</button>
        </div>
      </div>

      {{-- Anteprima --}}
      <div class="col-lg-7">
        <div class="card shadow-sm">
          <div class="card-header d-flex align-items-center">
            Anteprima
            <button type="button" id="btn-refresh" class="btn btn-sm btn-outline-primary ms-auto">Aggiorna anteprima</button>
          </div>
          <div class="card-body" id="dynform-preview">
            <x-dynform.render 
              :schema="$schema" 
              :layout="$layout" 
              :valori="[]" 
              mode="full" />
          </div>
        </div>
      </div>
    </div>
  </form>
</div>
@endsection

@push('scripts')
  {{-- JS core centralizzati --}}
  @include('partials.dynform-core')

<script>
(function(){
  // --- helpers base
  const sf = document.getElementById('schema_json');
  const lf = document.getElementById('layout_json');

  const read = el => { try { return JSON.parse(el.value); } catch(e){ alert('JSON non valido'); throw e; } };
  const write = (el,obj) => el.value = JSON.stringify(obj, null, 2);
  const slug = s => String(s || '').trim().toLowerCase().replace(/[^\w]+/g,'_').replace(/^_+|_+$/g,'');

  // --- UI: Campi -----------------------------------------------------------
  const listEl = document.getElementById('ui-fields-list');
  const editor = document.getElementById('ui-field-editor');
  const editorTitle = document.getElementById('ui-field-editor-title');
  const F = {
    idx: document.getElementById('f-index'),
    key: document.getElementById('f-key'),
    label: document.getElementById('f-label'),
    type: document.getElementById('f-type'),
    required: document.getElementById('f-required'),
    unit: document.getElementById('f-unit'),
    help: document.getElementById('f-help'),
    options: document.getElementById('f-options'),
    wrapOptions: document.getElementById('wrap-options'),
    min: document.getElementById('f-min'),
    max: document.getElementById('f-max'),
    step: document.getElementById('f-step'),
    pattern: document.getElementById('f-pattern'),
    wrapPattern: document.getElementById('wrap-pattern'),
    btnSave: document.getElementById('f-save'),
    btnCancel: document.getElementById('f-cancel'),
    btnDelete: document.getElementById('f-delete'),
  };

  function htmlesc(v){ return String(v??'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;'); }

  function renderFieldsUI(){
    if (!listEl) return;
    const sc = read(sf);
    const fields = Array.isArray(sc.fields) ? sc.fields : [];
    listEl.innerHTML = '';
    if (!fields.length) {
      listEl.innerHTML = '<div class="list-group-item text-muted">Nessun campo</div>';
      return;
    }
    fields.forEach((f, i) => {
      const li = document.createElement('div');
      li.className = 'list-group-item d-flex align-items-center';
      li.innerHTML = `
        <div class="me-2 badge bg-light text-dark">${htmlesc(f.key||'')}</div>
        <div class="flex-grow-1">
          <div><strong>${htmlesc(f.label||'')}</strong> <span class="text-muted">(${htmlesc(f.type||'text')})</span>${f.required?' <span class="badge bg-danger">req</span>':''}</div>
          ${f.help? `<div class="small text-muted">${htmlesc(f.help)}</div>`:''}
        </div>
        <div class="btn-group btn-group-sm">
          <button type="button" class="btn btn-outline-secondary" data-act="up" title="Su">↑</button>
          <button type="button" class="btn btn-outline-secondary" data-act="down" title="Giù">↓</button>
          <button type="button" class="btn btn-outline-primary" data-act="edit">Modifica</button>
          <button type="button" class="btn btn-outline-danger" data-act="del">Elimina</button>
        </div>`;
      li.querySelector('[data-act="edit"]').onclick = ()=> openFieldEditor(i);
      li.querySelector('[data-act="del"]').onclick = ()=> deleteField(i);
      li.querySelector('[data-act="up"]').onclick = ()=> moveField(i,-1);
      li.querySelector('[data-act="down"]').onclick = ()=> moveField(i,+1);
      listEl.appendChild(li);
    });
  }

  function openFieldEditor(index){
    if (!editor) return;
    const sc = read(sf);
    const fields = Array.isArray(sc.fields) ? sc.fields : [];
    const f = index!=null ? fields[index] : { key:'', label:'', type:'text', required:false, unit:'', help:'', options:[] };
    editor.classList.remove('d-none');
    if (editorTitle) editorTitle.textContent = index!=null ? `Modifica campo — ${f.key}` : 'Nuovo campo';
    F.idx.value = index!=null ? String(index) : '';
    F.key.value = f.key || '';
    F.label.value = f.label || '';
    F.type.value = f.type || 'text';
    F.required.value = f.required ? 'true' : 'false';
    F.unit.value = f.unit || '';
    F.help.value = f.help || '';
    F.options.value = Array.isArray(f.options) ? f.options.join(',') : '';
    if (F.wrapOptions) F.wrapOptions.style.display = (['select','radio','multi_select'].includes(F.type.value)) ? '' : 'none';
    const vld = f.validations || {};
    F.min.value = vld.min ?? '';
    F.max.value = vld.max ?? '';
    F.step.value = vld.step ?? '';
    F.pattern.value = vld.pattern ?? '';
    if (F.wrapPattern) F.wrapPattern.style.display = (['email','url','tel'].includes(F.type.value)) ? '' : 'none';
    if (F.btnDelete) F.btnDelete.classList.toggle('d-none', index==null);
  }

  function saveFieldFromEditor(){
    const idx = F.idx.value==='' ? null : parseInt(F.idx.value,10);
    const key = slug(F.key.value || F.label.value);
    if (!key) { alert('Key o Label obbligatori'); return; }

    const sc = read(sf);
    sc.fields = Array.isArray(sc.fields) ? sc.fields : [];
    const existsAt = sc.fields.findIndex((ff, i)=> i!==idx && (ff.key===key));
    if (existsAt!==-1) { alert('Esiste già un campo con questa key'); return; }

    const field = {
      key,
      label: F.label.value || key,
      type: F.type.value || 'text',
      required: F.required.value === 'true',
      unit: F.unit.value || null,
      help: F.help.value || null,
    };
    if (['select','radio','multi_select'].includes(field.type)) {
      field.options = (F.options.value||'').split(',').map(s=>s.trim()).filter(Boolean);
    }
    const vld = {};
    if (F.min.value !== '')  vld.min = Number(F.min.value);
    if (F.max.value !== '')  vld.max = Number(F.max.value);
    if (F.step.value !== '') vld.step = Number(F.step.value);
    if (F.pattern.value)     vld.pattern = F.pattern.value;
    if (Object.keys(vld).length) field.validations = vld;

    if (idx==null) sc.fields.push(field); else sc.fields[idx]=field;
    write(sf, sc);
    renderFieldsUI();
    closeFieldEditor();
  }

  function deleteField(index){
    if (!confirm('Eliminare questo campo?')) return;
    const sc = read(sf);
    const key = sc.fields[index]?.key;
    sc.fields.splice(index,1);
    write(sf, sc);

    // rimuovi dal layout tutte le celle con quella key
    const L = read(lf);
    (L.sections||[]).forEach(sec => (sec.rows||[]).forEach(row => {
      row.fields = (row.fields||[]).filter(cell => cell.key !== key);
    }));
    write(lf, L);
    renderFieldsUI();
    renderLayoutUI();
    closeFieldEditor();
  }

  function moveField(index, delta){
    const sc = read(sf);
    sc.fields = Array.isArray(sc.fields) ? sc.fields : [];
    const j = index + delta;
    if (j<0 || j>=sc.fields.length) return;
    [sc.fields[index], sc.fields[j]] = [sc.fields[j], sc.fields[index]];
    write(sf, sc);
    renderFieldsUI();
  }

  function closeFieldEditor(){ if (editor) editor.classList.add('d-none'); }

  document.getElementById('ui-add-field')?.addEventListener('click', ()=> openFieldEditor(null));
  F.type?.addEventListener('change', () => {
    const t = F.type.value;
    const needsOptions = ['select','radio','multi_select'].includes(t);
    if (F.wrapOptions) F.wrapOptions.style.display = needsOptions ? '' : 'none';
    const needsPattern = ['email','url','tel'].includes(t);
    if (F.wrapPattern) F.wrapPattern.style.display = needsPattern ? '' : 'none';
  });
  F.btnSave?.addEventListener('click', saveFieldFromEditor);
  F.btnCancel?.addEventListener('click', closeFieldEditor);
  F.btnDelete?.addEventListener('click', ()=> deleteField(parseInt(F.idx.value,10)));

  // --- UI: Layout ----------------------------------------------------------
  const secWrap = document.getElementById('ui-sections');

  function renderLayoutUI(){
    if (!secWrap) return;
    const L = read(lf); L.sections = Array.isArray(L.sections) ? L.sections : [];
    const fields = (read(sf).fields||[]);
    secWrap.innerHTML = '';

    if (!L.sections.length) {
      secWrap.innerHTML = '<div class="text-muted">Nessuna sezione</div>';
      return;
    }

    L.sections
      .slice()
      .sort((a,b)=>(a.order||0)-(b.order||0))
      .forEach((sec, sIdx) => {
        const card = document.createElement('div');
        card.className = 'border rounded p-2 mb-3';

        const rows = sec.rows || [];

        card.innerHTML = `
          <div class="d-flex align-items-center mb-2">
            <input class="form-control form-control-sm me-2" style="max-width: 320px" value="${htmlesc(sec.label||('Sezione '+(sIdx+1)))}" data-what="sec-label" />
            <span class="text-muted small me-2">#${htmlesc(sec.id||('sec'+(sIdx+1)))} / order ${htmlesc(sec.order||sIdx+1)}</span>
            <div class="btn-group btn-group-sm ms-auto">
              <button type="button" class="btn btn-outline-secondary" data-act="s-up">↑</button>
              <button type="button" class="btn btn-outline-secondary" data-act="s-down">↓</button>
              <button type="button" class="btn btn-outline-danger" data-act="s-del">Elimina</button>
            </div>
          </div>
          <div class="vstack gap-2" data-what="rows"></div>
          <div class="mt-2">
            <button type="button" class="btn btn-sm btn-outline-secondary" data-act="row-add">+ Riga</button>
          </div>
        `;

        const rowsHost = card.querySelector('[data-what="rows"]');
        rows.forEach((row, rIdx) => {
          const rowEl = document.createElement('div');
          rowEl.className = 'border rounded p-2';
          const cells = row.fields || [];
          rowEl.innerHTML = `
            <div class="d-flex align-items-center mb-2">
              <strong class="me-2">Riga ${rIdx+1}</strong>
              <div class="btn-group btn-group-sm ms-auto">
                <button type="button" class="btn btn-outline-secondary" data-act="r-up">↑</button>
                <button type="button" class="btn btn-outline-secondary" data-act="r-down">↓</button>
                <button type="button" class="btn btn-outline-danger" data-act="r-del">Elimina</button>
              </div>
            </div>
            <div class="d-flex flex-wrap gap-2 mb-2" data-what="cells"></div>
            <div class="d-flex gap-2">
              <select class="form-select form-select-sm" data-what="add-key" style="max-width: 260px">
                <option value="">-- campo --</option>
                ${fields.map(f=>`<option value="${htmlesc(f.key)}">${htmlesc(f.label||f.key)} (${htmlesc(f.key)})</option>`).join('')}
              </select>
              <input type="number" min="1" max="12" value="12" class="form-control form-control-sm" data-what="add-col" style="width:90px">
              <button type="button" class="btn btn-sm btn-outline-primary" data-act="cell-add">+ Campo</button>
            </div>
          `;
          const cellsHost = rowEl.querySelector('[data-what="cells"]');
          cells.forEach((cell, cIdx) => {
            const pill = document.createElement('div');
            pill.className = 'badge bg-light text-dark p-2';
            pill.innerHTML = `
              <span class="me-2">${htmlesc(cell.key)}</span>
              <span class="badge bg-secondary me-2">col ${htmlesc(cell.col||12)}</span>
              <button type="button" class="btn btn-sm btn-outline-danger" data-act="c-del">x</button>
            `;
            pill.querySelector('[data-act="c-del"]').onclick = ()=> {
              const L2 = read(lf);
              (L2.sections[sIdx].rows[rIdx].fields ||= []).splice(cIdx,1);
              write(lf, L2); renderLayoutUI();
            };
            cellsHost.appendChild(pill);
          });

          // actions riga
          rowEl.querySelector('[data-act="r-del"]').onclick = ()=>{
            const L2 = read(lf);
            L2.sections[sIdx].rows.splice(rIdx,1);
            write(lf, L2); renderLayoutUI();
          };
          rowEl.querySelector('[data-act="r-up"]').onclick = ()=>{
            const L2 = read(lf);
            if (rIdx>0) [L2.sections[sIdx].rows[rIdx-1], L2.sections[sIdx].rows[rIdx]] =
              [L2.sections[sIdx].rows[rIdx], L2.sections[sIdx].rows[rIdx-1]];
            write(lf, L2); renderLayoutUI();
          };
          rowEl.querySelector('[data-act="r-down"]').onclick = ()=>{
            const L2 = read(lf);
            if (rIdx < L2.sections[sIdx].rows.length-1)
              [L2.sections[sIdx].rows[rIdx+1], L2.sections[sIdx].rows[rIdx]] =
                [L2.sections[sIdx].rows[rIdx], L2.sections[sIdx].rows[rIdx+1]];
            write(lf, L2); renderLayoutUI();
          };

          // add cell
          rowEl.querySelector('[data-act="cell-add"]').onclick = ()=>{
            const key = rowEl.querySelector('[data-what="add-key"]').value;
            const col = Math.max(1, Math.min(12, parseInt(rowEl.querySelector('[data-what="add-col"]').value||12, 10)));
            if (!key) return;
            const L2 = read(lf);
            (L2.sections[sIdx].rows[rIdx].fields ||= []).push({ key, col });
            write(lf, L2); renderLayoutUI();
          };

          rowsHost.appendChild(rowEl);
        });

        // actions sezione
        card.querySelector('[data-act="row-add"]').onclick = ()=>{
          const L2 = read(lf);
          (L2.sections[sIdx].rows ||= []).push({ fields: [] });
          write(lf, L2); renderLayoutUI();
        };
        card.querySelector('[data-act="s-del"]').onclick = ()=>{
          if (!confirm('Eliminare la sezione?')) return;
          const L2 = read(lf);
          L2.sections.splice(sIdx,1);
          L2.sections.forEach((s,i)=> s.order = i+1);
          write(lf, L2); renderLayoutUI();
        };
        card.querySelector('[data-act="s-up"]').onclick = ()=>{
          const L2 = read(lf);
          if (sIdx>0) [L2.sections[sIdx-1], L2.sections[sIdx]] = [L2.sections[sIdx], L2.sections[sIdx-1]];
          L2.sections.forEach((s,i)=> s.order = i+1);
          write(lf, L2); renderLayoutUI();
        };
        card.querySelector('[data-act="s-down"]').onclick = ()=>{
          const L2 = read(lf);
          if (sIdx < L2.sections.length-1)
            [L2.sections[sIdx+1], L2.sections[sIdx]] = [L2.sections[sIdx], L2.sections[sIdx+1]];
          L2.sections.forEach((s,i)=> s.order = i+1);
          write(lf, L2); renderLayoutUI();
        };
        card.querySelector('[data-what="sec-label"]').onchange = (e)=>{
          const L2 = read(lf);
          L2.sections[sIdx].label = e.target.value;
          write(lf, L2);
        };

        secWrap.appendChild(card);
      });
  }

  document.getElementById('ui-add-section')?.addEventListener('click', ()=>{
    const L = read(lf);
    const idx = (L.sections?.length || 0) + 1;
    (L.sections ||= []).push({ id: 'sec'+idx, label:'Sezione '+idx, order: idx, rows:[{fields:[]}] });
    write(lf, L); renderLayoutUI();
  });

  // --- ANTEPRIMA -----------------------------------------------------------
  const btnR = document.getElementById('btn-refresh');
  btnR?.addEventListener('click', () => {
    let schemaObj, layoutObj;
    try { schemaObj = read(sf); } catch(e){ return; }
    try { layoutObj = read(lf); } catch(e){ return; }

    const host = document.querySelector('#dynform-preview .dynform-v2') || document.querySelector('.dynform-v2');
    if (!host) return;

    host.innerHTML = '';
    if (window.DynForm && typeof window.DynForm.renderInto === 'function') {
      window.DynForm.renderInto(host, schemaObj, layoutObj, {}, 'full');
    } else if (typeof window.renderDynamicFormV2 === 'function') {
      window.renderDynamicFormV2(host);
    } else {
      console.warn('[builder] renderer non disponibile');
    }
  });

  // inizializza UI
  renderFieldsUI();
  renderLayoutUI();
})();
</script>
@endpush
