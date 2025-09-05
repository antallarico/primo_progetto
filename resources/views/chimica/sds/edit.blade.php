@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Modifica SDS — {{ $sds->prodotto->nome_commerciale }}</h1>

  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form method="POST" action="{{ route('chimica.sds.update',$sds) }}" enctype="multipart/form-data">
    @csrf @method('PUT')

    <div class="row">
      <div class="col-md-3 mb-3">
        <label class="form-label">Data revisione *</label>
        <input type="date" name="data_revisione" class="form-control"
               value="{{ old('data_revisione', optional($sds->data_revisione)->format('Y-m-d')) }}" required>
      </div>
      <div class="col-md-3 mb-3">
        <label class="form-label">Rev. n°</label>
        <input name="rev_num" class="form-control" value="{{ old('rev_num', $sds->rev_num) }}">
      </div>
      <div class="col-md-2 mb-3">
        <label class="form-label">Lingua *</label>
        <input name="lingua" class="form-control" value="{{ old('lingua', $sds->lingua) }}" required>
      </div>
      <div class="col-md-4 mb-3">
        <label class="form-label">Sostituisci PDF (opz.)</label>
        <input type="file" name="file" accept="application/pdf" class="form-control">
        <div class="form-text">
          <a href="{{ route('chimica.sds.view',$sds) }}" target="_blank">Apri PDF attuale</a>
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-3 mb-3">
        <label class="form-label">Parola di avvertimento *</label>
        <select name="signal_word" class="form-select" required>
          @foreach(['Danger','Warning','None'] as $sw)
            <option value="{{ $sw }}" @selected(old('signal_word', $sds->clp->signal_word ?? 'None') === $sw)>{{ $sw }}</option>
          @endforeach
        </select>
      </div>
      <div class="col-md-9 mb-3">
        <label class="form-label d-block">Pittogrammi CLP</label>
        <div class="d-flex flex-wrap gap-3">
          @php $sel = collect(old('pittogrammi', $sds->clp->pittogrammi ?? []))->all(); @endphp
          @foreach(['GHS01','GHS02','GHS03','GHS04','GHS05','GHS06','GHS07','GHS08','GHS09'] as $g)
            <label class="form-check">
              <input type="checkbox" class="form-check-input" name="pittogrammi[]" value="{{ $g }}"
                     @checked(in_array($g, $sel))> {{ $g }}
            </label>
          @endforeach
        </div>
      </div>
    </div>

    <div class="row">
      <div class="col-md-6 mb-3">
        <label class="form-label">Frasi H (virgole)</label>
        <textarea name="frasi_h" class="form-control"
          placeholder="H225,H319,H336">{{ old('frasi_h', isset($sds->clp->frasi_h) ? implode(',',$sds->clp->frasi_h) : '') }}</textarea>
      </div>
      <div class="col-md-6 mb-3">
        <label class="form-label">Frasi P (virgole)</label>
        <textarea name="frasi_p" class="form-control"
          placeholder="P210,P233,P280">{{ old('frasi_p', isset($sds->clp->frasi_p) ? implode(',',$sds->clp->frasi_p) : '') }}</textarea>
      </div>
    </div>

    <div class="d-flex gap-2">
      <a href="{{ route('chimica.prodotti.sds.index', $sds->prodotto) }}" class="btn btn-light">Indietro</a>
      <button class="btn btn-primary">Salva modifiche</button>
    </div>
  </form>
</div>
@endsection

