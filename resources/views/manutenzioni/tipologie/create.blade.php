@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h2>Crea nuova Tipologia</h2>

    <form method="POST" action="{{ route('manutenzioni.tipologie.store') }}">
        @csrf
        @include('manutenzioni.tipologie._form', ['tipologia' => null])
    </form>
</div>
@endsection

