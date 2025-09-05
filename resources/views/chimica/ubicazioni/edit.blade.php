@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Modifica ubicazione — {{ $prodotto->nome_commerciale }}</h1>

  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach ($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form method="POST" action="{{ route('chimica.ubicazioni.update', $ubicazione) }}">
    @csrf @method('PUT')

    <div class="row">
      <div class="col-md-4 mb-3">
        <label class="form-label">Ubicazione *</label>
        <input name="ubicazione" class="form-control" required value="{{ old('ubicazione', $ubicazione->ubicazione) }}">
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Quantità *</label>
        <input type="number" step="0.01" min="0" name="quantita_disponibile" class="form-control" required
               value="{{ old('quantita_disponibile', number_format($ubicazione->quantita_disponibile, 2, '.', '')) }}">
      </div>
      <div class="col-md-2 mb-3">
        <label class="form-label">Unità *</label>
        <select name="unita" class="form-select" required>
          @foreach(['L','mL','kg','g','pz'] as $u)
            <option value="{{ $u }}" @selected(old('unita', $ubicazione->unita)===$u)>{{ $u }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Note</label>
        <input name="note" class="form-control" value="{{ old('note', $ubicazione->note) }}">
      </div>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('chimica.prodotti.ubicazioni.index', $prodotto) }}" class="btn btn-light">Indietro</a>
      <button class="btn btn-primary">Salva modifiche</button>
    </div>
  </form>
</div>
@endsection

