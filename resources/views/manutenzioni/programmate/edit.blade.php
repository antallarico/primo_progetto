@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h2>Modifica Manutenzione Programmata</h2>

    <form method="POST" action="{{ route('manutenzioni.programmate.update', $manutenzione->id) }}">
        @csrf @method('PUT')
        @include('manutenzioni.programmate._form', ['manutenzione' => $manutenzione])
    </form>
</div>
@endsection
