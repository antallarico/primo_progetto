@php $v = fn($f,$d=null)=>old($f, isset($tipologia)?($tipologia->$f ?? $d):$d); @endphp

<div class="row g-3">
  <div class="col-md-6">
    <label class="form-label">Nome *</label>
    <input type="text" name="nome" class="form-control" required value="{{ $v('nome') }}">
    @error('nome')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>

  <div class="col-md-6">
    <label class="form-label">Modello (checklist)</label>
    <select name="modello_id" class="form-select">
      <option value="">— Nessuno —</option>
      @foreach($modelli as $m)
        <option value="{{ $m->id }}" @selected($v('modello_id')==$m->id)>{{ $m->nome }}</option>
      @endforeach
    </select>
  </div>

  <div class="col-md-3">
    <label class="form-label">Validità (mesi)</label>
    <input type="number" name="validita_mesi" class="form-control" min="1" max="120" value="{{ $v('validita_mesi') }}">
  </div>

  <div class="col-md-9">
    <label class="form-label">Descrizione</label>
    <input type="text" name="descrizione" class="form-control" value="{{ $v('descrizione') }}">
  </div>

  <div class="col-md-3">
    <div class="form-check mt-4">
      <input class="form-check-input" type="checkbox" name="attiva" id="attiva" value="1" @checked($v('attiva',1))>
      <label class="form-check-label" for="attiva">Attiva</label>
    </div>
  </div>
</div>

