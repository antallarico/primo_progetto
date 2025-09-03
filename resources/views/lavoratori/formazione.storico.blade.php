@extends('layouts.app')

@section('title', 'Storico Formazione')

@section('content')
<div class="container">
    <h2 class="mb-4">📚 Storico Formazione - {{ $lavoratore->cognome }} {{ $lavoratore->nome }}</h2>

    @if ($partecipazioni->isEmpty())
        <div class="alert alert-info">Nessuna partecipazione registrata.</div>
    @else
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Data</th>
                    <th>Corso</th>
                    <th>Docente</th>
                    <th>Luogo</th>
                    <th>Attestato</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($partecipazioni as $p)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($p->data_formazione)->format('d/m/Y') }}</td>
                        <td>{{ $p->sessione->corso->titolo ?? '-' }}</td>
                        <td>{{ $p->sessione->docente ?? '-' }}</td>
                        <td>{{ $p->sessione->luogo ?? '-' }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $p->attestato)) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif

    <a href="{{ url()->previous() }}" class="btn btn-secondary mt-3">← Torna indietro</a>
</div>
@endsection
