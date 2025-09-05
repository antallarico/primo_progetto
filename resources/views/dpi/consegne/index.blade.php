@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Consegne DPI</h1>
  <div class="d-flex justify-content-between mb-3">
    <form method="GET" class="d-flex gap-2">
      <select name="lavoratore_id" class="form-select">
        <option value="">Tutti i lavoratori</option>
        @foreach($lavoratori as $l)
          <option value="{{ $l->id }}" @selected(request('lavoratore_id')==$l->id)>{{ $l->cognome }} {{ $l->nome }}</option>
        @endforeach
      </select>
      <select name="tipo_id" class="form-select">
        <option value="">Tutti i tipi</option>
        @foreach($tipi as $t)
          <option value="{{ $t->id }}" @selected(request('tipo_id')==$t->id)>{{ $t->nome }}</option>
        @endforeach
      </select>
      <select name="stato" class="form-select">
        <option value="">Stati</option>
        @foreach(['ATTIVA','SOSTITUITA','RESTITUITA','SCADUTA','ROTTAMATA'] as $s)
          <option value="{{ $s }}" @selected(request('stato')===$s)>{{ $s }}</option>
        @endforeach
      </select>
      <button class="btn btn-outline-secondary">Filtra</button>
    </form>
    <a href="{{ route('dpi.consegne.create') }}" class="btn btn-primary">Nuova Consegna</a>
  </div>

  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @error('stato') <div class="alert alert-danger">{{ $message }}</div> @enderror

  <table class="table table-striped">
    <thead><tr>
      <th>Lavoratore</th><th>DPI</th><th>Articolo</th><th>Q.ta</th><th>Consegna</th><th>Scadenza</th><th>Stato</th><th></th>
    </tr></thead>
    <tbody>
      @foreach($consegne as $c)
      <tr>
        <td>{{ $c->lavoratore->cognome ?? '' }} {{ $c->lavoratore->nome ?? '' }}</td>
        <td>{{ $c->articolo->tipo->nome ?? '-' }}</td>
        <td>{{ $c->articolo->marca ?? '' }} {{ $c->articolo->modello ?? '' }} {{ $c->articolo->taglia ? '('.$c->articolo->taglia.')' : '' }}</td>
        <td>{{ $c->quantita }}</td>
        <td>{{ $c->data_consegna?->format('d/m/Y') }}</td>
        <td>{{ $c->data_scadenza?->format('d/m/Y') ?? '-' }}</td>
        <td>{{ $c->stato }}</td>
        <td class="text-end">
          @if($c->stato === 'ATTIVA')
            <form method="POST" action="{{ route('dpi.consegne.restituzione',$c) }}" class="d-inline">
              @csrf <button class="btn btn-sm btn-outline-secondary">Restituzione</button>
            </form>
            <!-- Modal povero per Sostituzione -->
            <button class="btn btn-sm btn-outline-primary" onclick="openSost({{ $c->id }})">Sostituisci</button>
          @endif
        </td>
      </tr>
      @endforeach
    </tbody>
  </table>
  {{ $consegne->links() }}
</div>

<!-- Mini modal sostituzione (semplice) -->
<div class="modal" tabindex="-1" id="sostModal">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="sostForm" method="POST">
        @csrf
        <div class="modal-header"><h5 class="modal-title">Sostituzione DPI</h5></div>
        <div class="modal-body">
          <div class="mb-3">
            <label class="form-label">Nuovo articolo</label>
            <select class="form-select" name="nuovo_articolo_id" required>
              @foreach(\App\Models\DpiArticolo::with('tipo')->where('attivo',1)->get() as $a)
                <option value="{{ $a->id }}">{{ $a->tipo->nome ?? '' }} - {{ $a->marca }} {{ $a->modello }} {{ $a->taglia ? '('.$a->taglia.')' : '' }} [{{ $a->quantita_disponibile }}]</option>
              @endforeach
            </select>
          </div>
          <div class="row">
            <div class="col mb-3"><label class="form-label">Quantità</label><input name="quantita" type="number" min="1" value="1" class="form-control" required></div>
            <div class="col mb-3"><label class="form-label">Data consegna</label><input type="date" name="data_consegna" class="form-control" required value="{{ date('Y-m-d') }}"></div>
          </div>
          <div class="row">
            <div class="col mb-3"><label class="form-label">Primo utilizzo</label><input type="date" name="data_primo_utilizzo" class="form-control"></div>
            <div class="col mb-3"><label class="form-label">Scadenza (override)</label><input type="date" name="data_scadenza" class="form-control"></div>
          </div>
          <div class="mb-3"><label class="form-label">Motivo chiusura (vecchia)</label><input name="motivo_chiusura" class="form-control" value="usura"></div>
          <div class="mb-3"><label class="form-label">Note</label><textarea name="note" class="form-control"></textarea></div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-light" onclick="closeSost()">Annulla</button>
          <button class="btn btn-primary">Conferma</button>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
function openSost(id){
  const f = document.getElementById('sostForm');
  f.action = "{{ url('dpi/consegne') }}/"+id+"/sostituzione";
  document.getElementById('sostModal').style.display='block';
}
function closeSost(){
  document.getElementById('sostModal').style.display='none';
}
</script>
@endsection


