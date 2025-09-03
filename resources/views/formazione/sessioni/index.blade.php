@extends('layouts.app')

@section('title', 'Sessioni Formative')

@section('content')
<div class="container">
    <h2 class="mb-4">📅 Sessioni Formative</h2>

    <a href="{{ route('formazione.sessioni.create') }}" class="btn btn-primary mb-3">➕ Nuova Sessione</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Corso</th>
                <th>Data</th>
                <th>Durata (ore)</th>
                <th>Docente</th>
                <th>Luogo</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @forelse($sessioni as $sessione)
                <tr>
                    <td>{{ $sessione->corso->titolo }}</td>
                    <td>{{ \Carbon\Carbon::parse($sessione->data_sessione)->format('d/m/Y') }}</td>
                    <td>{{ $sessione->durata_effettiva }}</td>
                    <td>{{ $sessione->docente }}</td>
                    <td>{{ $sessione->luogo }}</td>
                    <td>
                        <a href="{{ route('formazione.sessioni.edit', $sessione) }}" class="btn btn-sm btn-warning" title="Modifica">✏️</a>

                        <form action="{{ route('formazione.sessioni.destroy', $sessione) }}" method="POST" class="d-inline" onsubmit="return confirm('Confermi l\'eliminazione?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger" title="Elimina">🗑️</button>
                        </form>

                        <a href="{{ route('formazione.registro.index', $sessione->id) }}" class="btn btn-sm btn-secondary" target="_blank" title="Registro Partecipanti">
                            📋 <span class="badge bg-light text-dark">{{ $sessione->partecipazioni_count }}</span>
                        </a>

                        <a href="{{ route('formazione.storicocorso', $sessione->corso_id) }}" class="btn btn-sm btn-info" target="_blank" title="Storico Corso">
                            📜
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Nessuna sessione trovata.</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
