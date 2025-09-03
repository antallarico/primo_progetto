@extends('layouts.app')

@section('title', 'Quadro Attrezzature')

@section('content')
<div class="container">
  <h1 class="h5 mb-3">📊 Quadro Attrezzature</h1>

  @php
    $tot   = $attrezzature->count();
    $tipOk = $attrezzature->whereNotNull('tipologia_id')->count();
    $schOk = $attrezzature->where('scheda_compilazioni_count', '>', 0)->count();
    $manOk = $attrezzature->where('interventi_count', '>', 0)->count();
  @endphp

  {{-- Riepilogo complessivo --}}
  <div class="text-muted small mb-2">
    Totali: Tipologia {{ $tipOk }}/{{ $tot }} • Scheda {{ $schOk }}/{{ $tot }} • Manutenzione {{ $manOk }}/{{ $tot }}
  </div>

  {{-- Filtri "mancanti" + modalità OR/AND --}}
  <div class="d-flex flex-wrap align-items-center gap-3 mb-2">
    <div class="form-check form-check-inline m-0">
      <input class="form-check-input" type="checkbox" id="miss-tip">
      <label class="form-check-label" for="miss-tip">Senza Tipologia</label>
    </div>
    <div class="form-check form-check-inline m-0">
      <input class="form-check-input" type="checkbox" id="miss-sch">
      <label class="form-check-label" for="miss-sch">Senza Scheda</label>
    </div>
    <div class="form-check form-check-inline m-0">
      <input class="form-check-input" type="checkbox" id="miss-man">
      <label class="form-check-label" for="miss-man">Senza Manutenzione</label>
    </div>

    <div class="d-flex align-items-center gap-2">
      <label for="miss-mode" class="mb-0 small text-muted">Criterio:</label>
      <select id="miss-mode" class="form-select form-select-sm">
        <option value="any">almeno una mancanza (OR)</option>
        <option value="all">tutte le selezioni mancanti (AND)</option>
      </select>
    </div>

    <span class="ms-auto text-muted small">
      Mostro <strong id="quadro-count-shown">{{ $tot }}</strong> / {{ $tot }}
    </span>
  </div>

  <table class="table table-sm table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th style="width: 40%;">Attrezzatura</th>
        <th style="width: 20%;">Tipologia</th>
        <th style="width: 20%;">Scheda</th>
        <th style="width: 20%;">Manutenzione</th>
      </tr>
    </thead>
    <tbody id="quadro-tbody">
      @forelse ($attrezzature as $a)
        @php
          $hasTip = !is_null($a->tipologia_id);
          $hasSch = ($a->scheda_compilazioni_count ?? 0) > 0;
          $hasMan = ($a->interventi_count ?? 0) > 0;
        @endphp

        <tr data-row="1"
            data-tip="{{ $hasTip ? '1' : '0' }}"
            data-sch="{{ $hasSch ? '1' : '0' }}"
            data-man="{{ $hasMan ? '1' : '0' }}">
          <td>
            <a href="{{ route('attrezzature.edit', $a) }}" class="text-decoration-none">{{ $a->nome }}</a>
            @if(!empty($a->matricola_azienda))
              <span class="text-muted ms-2">({{ $a->matricola_azienda }})</span>
            @endif
          </td>

          <td>
            @if($hasTip)
              <span class="badge bg-success">✓</span>
              <span class="small ms-1 text-muted">{{ $a->tipologia->nome ?? '' }}</span>
            @else
              <span class="badge bg-danger">✗</span>
            @endif
          </td>

          <td>
            @if($hasSch)
              <span class="badge bg-success">✓</span>
              <span class="small ms-1 text-muted">compilazioni: {{ $a->scheda_compilazioni_count }}</span>
            @else
              <span class="badge bg-danger">✗</span>
            @endif
          </td>

          <td>
            @if($hasMan)
              <span class="badge bg-success">✓</span>
              <span class="small ms-1 text-muted">interventi: {{ $a->interventi_count }}</span>
            @else
              <span class="badge bg-danger">✗</span>
            @endif
          </td>
        </tr>
      @empty
        <tr><td colspan="4" class="text-center">Nessuna attrezzatura.</td></tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- JS inline specifico per questa pagina --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
  const tbody = document.getElementById('quadro-tbody');
  const rows  = Array.from(tbody.querySelectorAll('tr[data-row]'));
  const count = document.getElementById('quadro-count-shown');

  const chkTip = document.getElementById('miss-tip');
  const chkSch = document.getElementById('miss-sch');
  const chkMan = document.getElementById('miss-man');
  const mode   = document.getElementById('miss-mode');

  function isMissing(tr, key) { return (tr.dataset[key] !== '1'); }

  function apply() {
    const wantTip = chkTip.checked;
    const wantSch = chkSch.checked;
    const wantMan = chkMan.checked;
    const how     = mode.value; // 'any' | 'all'

    // se nessun filtro selezionato, mostra tutto
    if (!wantTip && !wantSch && !wantMan) {
      let shown = 0;
      rows.forEach(tr => { tr.style.display = ''; shown++; });
      if (count) count.textContent = shown;
      return;
    }

    let shown = 0;
    rows.forEach(tr => {
      const missTip = isMissing(tr, 'tip');
      const missSch = isMissing(tr, 'sch');
      const missMan = isMissing(tr, 'man');

      let ok;
      if (how === 'all') {
        ok = true;
        if (wantTip) ok = ok && missTip;
        if (wantSch) ok = ok && missSch;
        if (wantMan) ok = ok && missMan;
      } else { // 'any'
        ok = false;
        if (wantTip) ok = ok || missTip;
        if (wantSch) ok = ok || missSch;
        if (wantMan) ok = ok || missMan;
      }

      tr.style.display = ok ? '' : 'none';
      if (ok) shown++;
    });

    if (count) count.textContent = shown;
  }

  [chkTip, chkSch, chkMan, mode].forEach(el => el.addEventListener('change', apply));
  apply(); // primo render
});
</script>
@endsection

