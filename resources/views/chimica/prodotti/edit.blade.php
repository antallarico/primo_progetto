@extends('layouts.app')
@section('content')
<div class="container">
  <h1>Modifica Prodotto Chimico</h1>
  @if ($errors->any())
    <div class="alert alert-danger"><ul class="mb-0">@foreach($errors->all() as $e)<li>{{ $e }}</li>@endforeach</ul></div>
  @endif
  <form method="POST" action="{{ route('chimica.prodotti.update', $prodotto) }}">
    @csrf @method('PUT')
    @include('chimica.prodotti._form')
    <div class="d-flex gap-2">
      <a href="{{ route('chimica.prodotti.index') }}" class="btn btn-light">Indietro</a>
      <button class="btn btn-primary">Salva modifiche</button>
    </div>
  </form>
</div>
@endsection

