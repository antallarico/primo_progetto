@extends('layouts.app')

@section('title', 'Elenco Attrezzature')

@section('content')
@php
  $lower = fn($s) => \Illuminate\Support\Str::of((string)$s)->lower();
@endphp

<div class="container" id="smartlist-root">
	<h1 class="h3 mb-4">📋 Elenco Attrezzature</h1>
  <div class="mb-3">
    <div class="d-flex flex-column gap-2">
      {{-- Riga 1: cinque filtri orizzontali --}}
      <div class="d-flex flex-wrap flex-md-nowrap align-items-center gap-2">
        <input id="filter-q" type="search" class="form-control form-control-sm"
               placeholder="Cerca nome, matricole, marca, note…"
               style="width: 380px;">

        <select id="filter-stato" class="form-select form-select-sm" style="width: 220px;">
          <option value="">Stato: tutti</option>
          @foreach (['in uso','fuori uso','dismessa'] as $st)
            <option value="{{ $st }}">{{ ucfirst($st) }}</option>
          @endforeach
        </select>

        <select id="filter-categoria" class="form-select form-select-sm" style="width: 220px;">
          <option value="">Categoria: tutte</option>
          @foreach ($categorie as $c)
            <option value="{{ $c->id }}">{{ $c->nome }}</option>
          @endforeach
        </select>

        <select id="filter-tipologia" class="form-select form-select-sm" style="width: 220px;">
          <option value="">Tipologia: tutte</option>
          @foreach ($tipologie as $t)
            <option value="{{ $t->id }}">{{ $t->nome }}</option>
          @endforeach
        </select>

        <select id="filter-ce" class="form-select form-select-sm" style="width: 220px;">
          <option value="">CE: tutti</option>
          <option value="1">CE</option>
          <option value="0">no CE</option>
          <option value="nd">n/d</option>
        </select>
      </div>

      {{-- Riga 2: tutto a sinistra, compatto --}}
<div class="d-flex align-items-center flex-wrap gap-2">
  <button id="filter-reset" type="button" class="btn btn-sm btn-outline-secondary">
    Azzera
  </button>

  <span class="text-muted small">
    Mostro <strong id="results-count">{{ count($attrezzature) }}</strong> righe
  </span>

  <a href="{{ route('attrezzature.create') }}" class="btn btn-primary btn-sm">
    ➕ Nuova Attrezzatura
  </a>
</div>

  <table id="attrezzature-table" class="table table-sm table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th data-sort-key="matricolaAzienda" data-sort-type="string" style="width:160px;">Matricola aziendale</th>
        <th data-sort-key="nome"             data-sort-type="string">Nome</th>
        <th data-sort-key="categoriaNome"    data-sort-type="string" style="width:190px;">Categoria</th>
        <th data-sort-key="tipologiaNome"    data-sort-type="string" style="width:190px;">Tipologia</th>
        <th data-sort-key="stato"            data-sort-type="string" style="width:110px;">Stato</th>
        <th data-sort-key="ce"               data-sort-type="string" style="width:100px;" class="text-center">CE</th>
        <th style="width:120px;" class="text-end">Azioni</th>
      </tr>
    </thead>
    <tbody>
      @forelse($attrezzature as $a)
        @php
          $ce = is_null($a->dich_ce) ? 'nd' : ($a->dich_ce ? '1' : '0');
          $text = $lower(
            implode(' ', [
              $a->nome, $a->marca, $a->modello, $a->matricola_azienda, $a->matricola,
              $a->ubicazione, $a->tipo, $a->note, $a->categoria->nome ?? '', $a->tipologia->nome ?? ''
            ])
          );
        @endphp

        {{-- Riga principale --}}
        <tr data-row="main"
            data-row-id="{{ $a->id }}"
            data-matricola-azienda="{{ $a->matricola_azienda ?? '' }}"
            data-nome="{{ $lower($a->nome) }}"
            data-categoria-nome="{{ $lower($a->categoria->nome ?? '') }}"
            data-tipologia-nome="{{ $lower($a->tipologia->nome ?? '') }}"
            data-stato="{{ $a->stato ?? '' }}"
            data-categoria-id="{{ $a->categoria_id ?? '' }}"
            data-tipologia-id="{{ $a->tipologia_id ?? '' }}"
            data-ce="{{ $ce }}"
            data-text="{{ $text }}"
        >
          <td>{{ $a->matricola_azienda ?? '—' }}</td>

          <td>
            <a class="text-decoration-none" data-bs-toggle="collapse" href="#det-{{ $a->id }}" role="button"
               aria-expanded="false" aria-controls="det-{{ $a->id }}">
              {{ $a->nome }}
            </a>
          </td>

          <td>{{ $a->categoria->nome ?? '—' }}</td>
          <td>{{ $a->tipologia->nome ?? '—' }}</td>

          <td>
            @php $st = $a->stato; @endphp
            <span class="badge {{ $st==='in uso' ? 'bg-success' : ($st==='fuori uso' ? 'bg-warning text-dark' : 'bg-secondary') }}">
              {{ $st ? ucfirst($st) : '—' }}
            </span>
          </td>

          <td class="text-center">
            @if ($ce === 'nd')
              <span class="badge bg-light text-muted">n/d</span>
            @elseif ($ce === '1')
              <span class="badge bg-success">CE</span>
            @else
              <span class="badge bg-danger">no CE</span>
            @endif
          </td>

          <td class="text-end text-nowrap">
            <a href="{{ route('attrezzature.edit', $a) }}"
               class="btn btn-sm btn-outline-primary me-1"
               title="{{ $a->scheda_compilazioni_count ? 'Scheda presente' : 'Nessuna scheda' }}">
              @if($a->scheda_compilazioni_count) ✏️✏️ @else ✏️ @endif
            </a>
            <form action="{{ route('attrezzature.destroy', $a) }}" method="POST" class="d-inline"
                  onsubmit="return confirm('Eliminare «{{ $a->nome }}»?');">
              @csrf @method('DELETE')
              <button type="submit" class="btn btn-sm btn-outline-danger" title="Elimina">🗑️</button>
            </form>
          </td>
        </tr>

        {{-- Riga dettagli --}}
        <tr class="collapse" id="det-{{ $a->id }}" data-row-id="{{ $a->id }}">
          <td colspan="7" class="bg-light">
            <div class="row g-3 small py-2">
              <div class="col-md-3"><strong>Marca:</strong> {{ $a->marca ?? '—' }}</div>
              <div class="col-md-3"><strong>Modello:</strong> {{ $a->modello ?? '—' }}</div>
              <div class="col-md-3"><strong>Matricola (fabbricante):</strong> {{ $a->matricola ?? '—' }}</div>
              <div class="col-md-3"><strong>Fabbricazione:</strong> {{ $a->data_fabbricazione ?? '—' }}</div>

              <div class="col-md-4"><strong>Ubicazione:</strong> {{ $a->ubicazione ?? '—' }}</div>
              <div class="col-md-4"><strong>Padre:</strong> {{ $a->attrezzaturaPadre->nome ?? '—' }}</div>
              <div class="col-md-4"><strong>Tipo (libero):</strong> {{ $a->tipo ?? '—' }}</div>

              <div class="col-12">
                <strong>Note:</strong> {{ \Illuminate\Support\Str::limit($a->note, 300) ?? '—' }}
              </div>
            </div>
          </td>
        </tr>
      @empty
        <tr>
          <td colspan="7" class="text-center">Nessuna attrezzatura trovata.</td>
        </tr>
      @endforelse
    </tbody>
  </table>
</div>

{{-- JS esterno riusabile --}}
@pushOnce('scripts')
  <script src="{{ asset('js/listing/smartlist.js') }}?v={{ @filemtime(public_path('js/listing/smartlist.js')) }}"></script>
@endPushOnce
@endsection
