@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Nuovo Articolo DPI</h1>

  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif

  <form method="POST" action="{{ route('dpi.articoli.store') }}">
    @csrf
    @include('dpi.articoli._form')
    <div class="d-flex gap-2">
      <a href="{{ route('dpi.articoli.index') }}" class="btn btn-light">Annulla</a>
      <button class="btn btn-primary">Salva</button>
    </div>
  </form>
</div>
@endsection




