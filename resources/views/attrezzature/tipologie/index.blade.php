@extends('layouts.app')

@section('title', 'Tipologie Attrezzature')

@section('content')
<div class="container">
    <h2 class="mb-4">📂 Tipologie Attrezzature</h2>

    <a href="{{ route('attrezzature.tipologie.create') }}" class="btn btn-primary mb-3">➕ Nuova Tipologia</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nome</th>
				<th>Has Scheda</th>
                <th>scheda_tabella</th>
                <th>scheda_view</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @forelse($tipologie as $tipologia)
                <tr>
                    <td>{{ $tipologia->nome }}</td>
					<td>{{ $tipologia->has_scheda ? '✅' : '❌' }}</td>
                    <td>{{ $tipologia->scheda_tabella ?? '—' }}</td>
                    <td>{{ $tipologia->scheda_view ?? '—' }}</td>
                    <td>
                        <a href="{{ route('attrezzature.tipologie.edit', $tipologia) }}" class="btn btn-sm btn-warning">✏️</a>
                        <form action="{{ route('attrezzature.tipologie.destroy', $tipologia) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminare la tipologia?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="2" class="text-center">Nessuna tipologia trovata.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
