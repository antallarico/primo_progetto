@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h2>Tipologie di Manutenzione</h2>

    <a href="{{ route('manutenzioni.tipologie.create') }}" class="btn btn-primary mb-3">➕ Nuova Tipologia</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Periodicità (mesi)</th>
                <th>Obbligatoria</th>
                <th>Modelli dinamici</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($tipologie as $tipologia)
                <tr>
                    <td>{{ $tipologia->nome }}</td>
                    <td>{{ $tipologia->periodicita_mesi ?? '-' }}</td>
                    <td>{{ $tipologia->obbligatoria ? 'Sì' : 'No' }}</td>
                    <td>
                        {{ $tipologia->modelli_dinamici_count ?? $tipologia->modelliDinamici->count() }}
                        @if(($tipologia->modelliDinamici ?? collect())->count())
                            <div class="small text-muted">
                                {{ $tipologia->modelliDinamici->pluck('nome')->join(', ') }}
                            </div>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('manutenzioni.tipologie.edit', $tipologia->id) }}" class="btn btn-sm btn-secondary">✏️ Modifica</a>
                        <form action="{{ route('manutenzioni.tipologie.destroy', $tipologia->id) }}" method="POST" style="display:inline-block;" onsubmit="return confirm('Eliminare questa tipologia?')">
                            @csrf @method('DELETE')
                            <button class="btn btn-sm btn-danger">🗑️ Elimina</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

