@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Nuova Consegna DPI</h1>
  <form method="POST" action="{{ route('dpi.consegne.store') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label">Lavoratore *</label>
      <select name="lavoratore_id" class="form-select" required>
        @foreach($lavoratori as $l)
          <option value="{{ $l->id }}">{{ $l->cognome }} {{ $l->nome }}</option>
        @endforeach
      </select>
    </div>
    <div class="mb-3">
      <label class="form-label">Articolo *</label>
      <select name="articolo_id" class="form-select" required>
        @foreach($articoli as $a)
          <option value="{{ $a->id }}">
            {{ $a->tipo->nome ?? '' }} — {{ $a->marca }} {{ $a->modello }} {{ $a->taglia ? '('.$a->taglia.')' : '' }}
            [disp: {{ $a->quantita_disponibile }}]
          </option>
        @endforeach
      </select>
    </div>
    <div class="row">
      <div class="col-md-3 mb-3"><label class="form-label">Quantità *</label><input type="number" name="quantita" min="1" value="1" class="form-control" required></div>
      <div class="col-md-3 mb-3"><label class="form-label">Data consegna *</label><input type="date" name="data_consegna" class="form-control" required value="{{ date('Y-m-d') }}"></div>
      <div class="col-md-3 mb-3"><label class="form-label">Primo utilizzo</label><input type="date" name="data_primo_utilizzo" class="form-control"></div>
      <div class="col-md-3 mb-3"><label class="form-label">Scadenza (override)</label><input type="date" name="data_scadenza" class="form-control"></div>
    </div>
    <div class="mb-3"><label class="form-label">Note</label><textarea name="note" class="form-control"></textarea></div>
    <button class="btn btn-primary">Salva</button>
  </form>
</div>
@endsection


