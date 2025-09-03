@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h2>Modifica Intervento</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <strong>⚠️ Ci sono errori nella compilazione:</strong>
            <ul>
                @foreach ($errors->all() as $errore)
                    <li>{{ $errore }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('manutenzioni.registro.update', $intervento->id) }}">
        @csrf
        @method('PUT')

        {{-- campi statici + select tipologia/modello --}}
        @include('manutenzioni.registro._form', ['intervento' => $intervento])

    </form>
</div>
@endsection

@push('scripts')
  {{-- i core vengono caricati con @pushOnce dal wrapper; tenerlo qui non crea doppioni --}}
  @include('partials.dynform-core')
@endpush
