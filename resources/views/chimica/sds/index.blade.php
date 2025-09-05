@extends('layouts.app')
@section('content')
<div class="container">
  <h1>SDS — {{ $prodotto->nome_commerciale }}</h1>

  @if(session('ok')) <div class="alert alert-success">{{ session('ok') }}</div> @endif
  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <div class="card mb-3">
    <div class="card-body">
      <form method="POST" action="{{ route('chimica.prodotti.sds.store', $prodotto) }}" enctype="multipart/form-data">
        @csrf
        <div class="row">
          <div class="col-md-3 mb-3">
            <label class="form-label">Data revisione *</label>
            <input type="date" name="data_revisione" class="form-control" required>
          </div>
          <div class="col-md-3 mb-3">
            <label class="form-label">Rev. n°</label>
            <input name="rev_num" class="form-control">
          </div>
          <div class="col-md-2 mb-3">
            <label class="form-label">Lingua *</label>
            <input name="lingua" class="form-control" value="IT" required>
          </div>
          <div class="col-md-4 mb-3">
            <label class="form-label">File PDF *</label>
            <input type="file" name="file" accept="application/pdf" required class="form-control">
          </div>
        </div>

        <div class="row">
          <div class="col-md-3 mb-3">
            <label class="form-label">Parola di avvertimento *</label>
            <select name="signal_word" class="form-select" required>
              @foreach(['Danger','Warning','None'] as $sw)
                <option value="{{ $sw }}">{{ $sw }}</option>
              @endforeach
            </select>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label d-block">Pittogrammi CLP</label>
            <div class="d-flex flex-wrap gap-2">
              @foreach(['GHS01','GHS02','GHS03','GHS04','GHS05','GHS06','GHS07','GHS08','GHS09'] as $g)
                <label class="form-check">
                  <input type="checkbox" class="form-check-input" name="pittogrammi[]" value="{{ $g }}"> {{ $g }}
                </label>
              @endforeach
            </div>
          </div>
        </div>

        <div class="row">
          <div class="col-md-6 mb-3">
            <label class="form-label">Frasi H (separate da virgola)</label>
            <textarea name="frasi_h" class="form-control" placeholder="H225,H319,H336"></textarea>
          </div>
          <div class="col-md-6 mb-3">
            <label class="form-label">Frasi P (separate da virgola)</label>
            <textarea name="frasi_p" class="form-control" placeholder="P210,P233,P280"></textarea>
          </div>
        </div>

        <div class="d-flex gap-2">
          <a href="{{ route('chimica.prodotti.index') }}" class="btn btn-light">Indietro</a>
          <button class="btn btn-primary">Carica SDS</button>
        </div>
      </form>
    </div>
  </div>

<h5>Storico SDS</h5>
	<table class="table table-striped">
		<thead>
			<tr>
			<th>Data rev.</th>
			<th>Rev</th>
			<th>Lingua</th>
			<th>Stato</th>
			<th>CLP</th>
			<th></th>
			</tr>
		</thead>
		<tbody>
			@foreach($sds as $row)
			<tr>
			<td>{{ $row->data_revisione?->format('d/m/Y') }}</td>
			<td>{{ $row->rev_num }}</td>
			<td>{{ $row->lingua }}</td>
			<td>
				@if($row->id === $corrente?->id)
					<span class="badge bg-success">Corrente</span>
				@else
					<span class="badge bg-secondary">Storico</span>
				@endif
			</td>
			<td>
				@php $g = $row->clp?->pittogrammi ?? []; @endphp
				{{ $row->clp?->signal_word ?? '—' }} |
				@if($g) {{ implode(', ', $g) }} @else — @endif |
				@if($row->clp?->frasi_h) H: {{ implode(', ', $row->clp->frasi_h) }} @endif
			</td>
			<td class="text-end">
				<a href="{{ route('chimica.sds.download', $row) }}" class="btn btn-sm btn-outline-secondary">Scarica</a>
				<form action="{{ route('chimica.sds.destroy',$row) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminare questa SDS?')">
					@csrf @method('DELETE')
					<button class="btn btn-sm btn-danger">Elimina</button>
				</form>
			</td>
			</tr>
			@endforeach
		</tbody>
</table>

</div>
@endsection


