@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Nuovo Tipo DPI</h1>
  <form method="POST" action="{{ route('dpi.tipi.store') }}">
    @csrf
    <div class="mb-3">
      <label class="form-label">Nome *</label>
      <input name="nome" class="form-control" required value="{{ old('nome') }}">
    </div>
    <div class="mb-3">
      <label class="form-label">Categoria</label>
      <select name="categoria" class="form-select">
        <option value="">-</option>
        @foreach(['I','II','III'] as $c)
          <option value="{{ $c }}" @selected(old('categoria')===$c)>{{ $c }}</option>
        @endforeach
      </select>
    </div>
    <div class="mb-3"><label class="form-label">Norma EN</label><input name="norma_en" class="form-control" value="{{ old('norma_en') }}"></div>
    <div class="mb-3"><label class="form-label">Rischi coperti</label><textarea name="rischi_coperti" class="form-control">{{ old('rischi_coperti') }}</textarea></div>
    <div class="mb-3">
      <label class="form-label">Politica scadenza (JSON)</label>
      <textarea name="politica_scadenza_default" class="form-control" placeholder='{"base":"mesi_dal_primo_utilizzo","valore":24}'>{{ old('politica_scadenza_default') }}</textarea>
      <div class="form-text">Esempi: {"base":"mesi_dalla_consegna","valore":36} — {"base":"nessuna_scadenza"}</div>
    </div>
    <div class="mb-3"><label class="form-label">Note</label><textarea name="note" class="form-control">{{ old('note') }}</textarea></div>
    <div class="form-check mb-3"><input type="checkbox" class="form-check-input" name="attivo" value="1" checked> <label class="form-check-label">Attivo</label></div>
    <button class="btn btn-primary">Salva</button>
  </form>
</div>
@endsection


