
@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Modifica Scheda Dinamica</h2>
  
<form method="POST" action="{{ route('attrezzature.schede.update', ['scheda' => $scheda->id]) }}">

        @csrf
        @method('PUT')

        @include('attrezzature.schede._form', ['scheda' => $scheda])

        {{-- ✅ Pulsanti --}}
        <div class="mt-3">
            <button type="submit" class="btn btn-success">💾 Salva modifiche</button>
            <a href="{{ route('attrezzature.schede.index') }}" class="btn btn-secondary">Annulla</a>
        </div>
    </form>
</div>
@endsection
