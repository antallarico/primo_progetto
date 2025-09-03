@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h2>Registro Manutenzioni</h2>

    <a href="{{ route('manutenzioni.registro.create') }}" class="btn btn-primary mb-3">➕ Nuovo Intervento</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-sm align-middle">
        <thead>
            <tr>
                <th>Data</th>
                <th>Attrezzatura</th>
                <th>Tipologia</th>
                <th>Competenza</th>
                <th>Esito</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($interventi as $i)
                <tr>
                    <td>
                        @if($i->data_esecuzione)
                            {{ \Carbon\Carbon::parse($i->data_esecuzione)->format('d/m/Y') }}
                        @else
                            -
                        @endif
                    </td>
                    <td>{{ $i->attrezzatura->nome ?? '-' }}</td>
                    <td>{{ $i->tipologia->nome ?? '-' }}</td>
                    <td>{{ $i->competenza->nome ?? '-' }}</td>
                    <td>{{ $i->esito ?? '-' }}</td>
                    <td>
                        <a href="{{ route('manutenzioni.registro.edit', $i->id) }}" class="btn btn-sm btn-secondary">✏️</a>
                        <form action="{{ route('manutenzioni.registro.destroy', $i->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminare questo intervento?')">
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

