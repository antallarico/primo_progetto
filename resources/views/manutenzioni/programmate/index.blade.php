@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h2>Manutenzioni Programmate</h2>

    <a href="{{ route('manutenzioni.programmate.create') }}" class="btn btn-primary mb-3">➕ Nuova Manutenzione</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-sm align-middle">
        <thead>
            <tr>
                <th>Attrezzatura</th>
                <th>Tipologia</th>
                <th>Competenza</th>
                <th>Frequenza</th>
                <th>Prossima Scadenza</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($manutenzioni as $m)
                <tr>
                    <td>{{ $m->attrezzatura->nome ?? '-' }}</td>
                    <td>{{ $m->tipologia->nome ?? '-' }}</td>
                    <td>{{ $m->competenza->nome ?? '-' }}</td>
                    <td>{{ $m->frequenza_mesi ? $m->frequenza_mesi . ' mesi' : '-' }}</td>
                    <td>{{ $m->scadenza_prossima ? \Carbon\Carbon::parse($m->scadenza_prossima)->format('d/m/Y') : '-' }}</td>
                    <td>
                        <a href="{{ route('manutenzioni.programmate.edit', $m->id) }}" class="btn btn-sm btn-secondary">✏️</a>
                        <form action="{{ route('manutenzioni.programmate.destroy', $m->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminare questa voce?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
