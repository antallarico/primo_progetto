@php
  $v       = fn($f,$d=null)=>old($f, isset($addestramento)?($addestramento->$f ?? $d):$d);
  $selLav  = $v('lavoratore_id');
  $selAtt  = old('attrezzatura_id', $attrezzaturaId ?? $v('attrezzatura_id'));
  $selTip  = old('tipologia_id',   $tipologiaId   ?? $v('tipologia_id'));
  $selMod  = old('modello_id',     $modello->id   ?? $v('modello_id'));
  $ambito  = old('ambito', $addestramento->ambito ?? 'attrezzatura');
  $selMany = old('attrezzature_ids', $selectedAttrezzature ?? []);
@endphp

<div class="row g-3">
  <div class="col-md-4">
    <label class="form-label">Lavoratore *</label>
    <select name="lavoratore_id" class="form-select" required>
      <option value="">— seleziona —</option>
      @foreach($lavoratori as $l)
        <option value="{{ $l->id }}" @selected((string)$selLav===(string)$l->id)>{{ $l->cognome }} {{ $l->nome }}</option>
      @endforeach
    </select>
    @error('lavoratore_id')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Attrezzatura</label>
    <select name="attrezzatura_id" class="form-select">
      <option value="">— nessuna —</option>
      @foreach($attrezzature as $a)
        <option value="{{ $a->id }}" @selected((string)$selAtt===(string)$a->id)>{{ $a->nome }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label">Data *</label>
    <input
      type="date"
      name="data_addestramento"
      class="form-control"
      required
      value="{{ old('data_addestramento', isset($addestramento) && $addestramento->data_addestramento ? $addestramento->data_addestramento->format('Y-m-d') : now()->toDateString()) }}"
    >
    @error('data_addestramento')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Tipologia</label>
    <select id="tipologia_id" name="tipologia_id" class="form-select">
      <option value="">— nessuna —</option>
      @foreach($tipologie as $t)
        <option value="{{ $t->id }}" @selected((string)$selTip===(string)$t->id)>{{ $t->nome }}</option>
      @endforeach
    </select>
    @error('tipologia_id')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-4">
    <label class="form-label">Modello (checklist)</label>
    <select id="modello_id" name="modello_id" class="form-select">
      <option value="">— nessuno —</option>
      @foreach($modelli as $m)
        <option value="{{ $m->id }}" @selected((string)$selMod===(string)$m->id)>{{ $m->nome }}</option>
      @endforeach
    </select>
    @error('modello_id')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  {{-- Filtro condiviso Tipologia → Modello (inietta anche window.modelliDinamici) --}}
  <x-dynform.filter :modelli="$modelli" tipSelect="#tipologia_id" modelSelect="#modello_id" />

  <div class="col-md-4">
    <label class="form-label">Esito</label>
    <select name="esito" class="form-select">
      @php $es = $v('esito'); @endphp
      <option value="">— n/d —</option>
      <option value="idoneo"            @selected($es==='idoneo')>Idoneo</option>
      <option value="non_idoneo"        @selected($es==='non_idoneo')>Non idoneo</option>
      <option value="in_affiancamento"  @selected($es==='in_affiancamento')>In affiancamento</option>
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label">Istruttore (lavoratore)</label>
    <select name="istruttore_id" class="form-select">
      <option value="">— nessuno —</option>
      @foreach($lavoratori as $l)
        <option value="{{ $l->id }}" @selected((string)$v('istruttore_id')===(string)$l->id)>{{ $l->cognome }} {{ $l->nome }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-4">
    <label class="form-label">Istruttore (nome libero)</label>
    <input type="text" name="istruttore_nome" class="form-control" value="{{ $v('istruttore_nome') }}">
  </div>

  <div class="col-12">
    <label class="form-label">Note</label>
    <textarea name="note" rows="2" class="form-control">{{ $v('note') }}</textarea>
  </div>

  {{-- AMBITO (valido per) --}}
  <div class="col-md-12">
    <label class="form-label d-block mb-1">Valido per</label>
    <div class="d-flex flex-wrap gap-3">
      <div class="form-check">
        <input class="form-check-input" type="radio" name="ambito" id="ambito-att" value="attrezzatura" @checked($ambito==='attrezzatura')>
        <label class="form-check-label" for="ambito-att">Solo questa attrezzatura</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="ambito" id="ambito-tip" value="tipologia" @checked($ambito==='tipologia')>
        <label class="form-check-label" for="ambito-tip">Tutte della tipologia selezionata</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="ambito" id="ambito-sel" value="selezione" @checked($ambito==='selezione')>
        <label class="form-check-label" for="ambito-sel">Selezione personalizzata</label>
      </div>
    </div>
  </div>

  {{-- Multi-select attrezzature (solo per "selezione") --}}
  <div class="col-12" id="box-selezione" style="display:none;">
    <label class="form-label">Applica anche a queste attrezzature</label>
    <select name="attrezzature_ids[]" id="attrezzature_ids" class="form-select" multiple size="6">
      @foreach($attrezzature as $a)
        <option value="{{ $a->id }}" @selected(in_array($a->id, (array)$selMany, true))>
          {{ $a->nome }} @if($a->tipologia?->nome) — {{ $a->tipologia->nome }} @endif
        </option>
      @endforeach
    </select>
    <div class="form-text">Tieni premuto CTRL (Windows) o ⌘ (Mac) per selezioni multiple.</div>
    @error('attrezzature_ids')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  {{-- DynForm mount --}}
  <div class="col-12">
    <x-dynform.mount
      select="#modello_id"
      :values="$values ?? []"
      :modello="$modello ?? null"
      :base="url('modelli-dinamici')"
      mode="embed"
    />
    @error('payload')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
</div>

@push('scripts')
<script>
(function(){
  const rAtt = document.getElementById('ambito-att');
  const rTip = document.getElementById('ambito-tip');
  const rSel = document.getElementById('ambito-sel');
  const box  = document.getElementById('box-selezione');
  function toggleBox() {
    if (!box) return;
    box.style.display = (rSel && rSel.checked) ? '' : 'none';
  }
  [rAtt, rTip, rSel].forEach(el => el && el.addEventListener('change', toggleBox));
  toggleBox(); // primo render
})();
</script>
@endpush
