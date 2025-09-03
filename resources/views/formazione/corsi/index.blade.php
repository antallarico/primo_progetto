@extends('layouts.app')

@section('title', 'Elenco Corsi di Formazione')

@section('content')
<div class="container">
    <h2 class="mb-4">📚 Elenco Corsi di Formazione</h2>

    <a href="{{ route('formazione.corsi.create') }}" class="btn btn-primary mb-3">➕ Nuovo Corso</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Codice</th>
                <th>Titolo</th>
                <th>Durata (h)</th>
                <th>Validità (mesi)</th>
                <th>Obbligatorio</th>
                <th>Normato</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @forelse($corsi as $corso)
                <tr>
                    <td>{{ $corso->codice }}</td>
                    <td>{{ $corso->titolo }}</td>
                    <td>{{ $corso->durata_ore }}</td>
                    <td>{{ $corso->validita_mesi }}</td>
                    <td>{{ $corso->obbligatorio ? '✅' : '❌' }}</td>
                    <td>{{ $corso->normato ? '✅' : '❌' }}</td>
                    <td>
                        <a href="{{ route('formazione.corsi.edit', $corso) }}" class="btn btn-sm btn-warning" title="Modifica">✏️</a>

                        <a href="{{ route('formazione.storicocorso', $corso->id) }}" class="btn btn-sm btn-info" title="Storico Corso">
                            📜
                        </a>

                        <form action="{{ route('formazione.corsi.destroy', $corso) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminare questo corso?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Elimina">🗑️</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">Nessun corso trovato.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection

