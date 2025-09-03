@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Nuova Scheda Dinamica</h2>

    <form method="POST" action="{{ route('attrezzature.schede.store') }}">
        @csrf
        @include('attrezzature.schede._form', ['scheda' => null])

        {{-- ✅ Pulsanti di azione --}}
        <div class="mt-3">
            <button type="submit" class="btn btn-success">💾 Salva</button>
            <a href="{{ route('attrezzature.schede.index') }}" class="btn btn-secondary">Annulla</a>
        </div>
    </form>
</div>
@endsection
