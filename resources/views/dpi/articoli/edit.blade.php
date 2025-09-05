@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Modifica Articolo DPI</h1>

  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form method="POST" action="{{ route('dpi.articoli.update', $articolo) }}">
    @csrf @method('PUT')
    @include('dpi.articoli._form')
    <div class="d-flex gap-2">
      <a href="{{ route('dpi.articoli.index') }}" class="btn btn-light">Indietro</a>
      <button class="btn btn-primary">Salva modifiche</button>
    </div>
  </form>
</div>
@endsection
