@extends('layouts.app')

@section('content')
<div class="container py-3">
  <h2>Nuovo Modello (v2)</h2>

  @if($errors->any())
    <div class="alert alert-danger">{!! implode('<br>',$errors->all()) !!}</div>
  @endif

  <form method="POST" action="{{ route('modelli_dinamici.store') }}" class="mt-2">
    @csrf
    <div class="mb-3">
      <label class="form-label">Nome *</label>
      <input name="nome" class="form-control" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Modulo *</label>
      <input name="modulo" class="form-control" placeholder="Es. Manutenzioni" required>
    </div>
    <div class="mb-3">
      <label class="form-label">Tipologia (ID) — opzionale</label>
      <input name="tipologia_id" class="form-control" placeholder="ID tipologia, se serve">
    </div>
    <div class="mb-3">
      <label class="form-label">Stato *</label>
      <select name="stato" class="form-select" required>
        <option value="bozza">Bozza</option>
        <option value="pubblicato">Pubblicato</option>
      </select>
    </div>
    <button class="btn btn-primary">Crea</button>
    <a href="{{ route('modelli_dinamici.index') }}" class="btn btn-secondary">Annulla</a>
  </form>
</div>
@endsection

