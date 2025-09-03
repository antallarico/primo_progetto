@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h2>Nuovo Intervento</h2>

    <form method="POST" action="{{ route('manutenzioni.registro.store') }}">
        @csrf

        {{-- campi statici + select tipologia/modello --}}
        @include('manutenzioni.registro._form', ['intervento' => null])

    </form>
</div>
@endsection


