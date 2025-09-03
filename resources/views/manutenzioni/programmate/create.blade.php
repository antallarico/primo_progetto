@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h2>Nuova Manutenzione Programmata</h2>

    <form method="POST" action="{{ route('manutenzioni.programmate.store') }}">
        @csrf
        @include('manutenzioni.programmate._form', ['manutenzione' => null])
    </form>
</div>
@endsection
