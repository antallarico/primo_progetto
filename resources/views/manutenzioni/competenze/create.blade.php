@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h2>Nuova Competenza / Manutentore</h2>

    <form method="POST" action="{{ route('manutenzioni.competenze.store') }}">
        @csrf
        @include('manutenzioni.competenze._form', ['competenza' => null])
    </form>
</div>
@endsection
