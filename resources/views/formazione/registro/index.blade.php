@extends('layouts.app')

@section('title', 'Registro Partecipanti')

@section('content')
<div class="container">
	
	<h2 class="mb-2">📋 Registro Partecipanti</h2>
		<p class="mb-4">
		<strong>Corso:</strong> {{ $sessione->corso->titolo }}<br>
		<strong>Data:</strong> {{ \Carbon\Carbon::parse($sessione->data_sessione)->format('d/m/Y') }}<br>
		<strong>Docente:</strong> {{ $sessione->docente ?? '—' }}<br>
		<strong>Soggetto Formatore:</strong> {{ $sessione->soggetto_formatore ?? '—' }}<br>
		<strong>Luogo:</strong> {{ $sessione->luogo ?? '—' }}
	</p>

    <a href="{{ route('formazione.registro.create', $sessione->id) }}" class="btn btn-success mb-3">➕ Aggiungi Partecipante</a>

    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>Nome</th>
                <th>Cognome</th>
                <th>Data Formazione</th>
                <th>Data Scadenza</th>
                <th>Attestato</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @forelse($partecipazioni as $p)
                <tr>
                    <td>{{ $p->lavoratore->nome }}</td>
                    <td>{{ $p->lavoratore->cognome }}</td>
                    <td>{{ $p->data_formazione }}</td>
                    <td>{{ $p->data_scadenza ?? '—' }}</td>
                    <td>{{ ucfirst(str_replace('_', ' ', $p->attestato)) }}</td>
                    <td>
                        <a href="{{ route('formazione.registro.edit', $p->id) }}" class="btn btn-sm btn-warning">✏️</a>
                        <form action="{{ route('formazione.registro.destroy', $p->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Confermi eliminazione?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger">🗑️</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center">Nessuna partecipazione registrata.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <a href="{{ route('formazione.sessioni.index') }}" class="btn btn-secondary mt-3">← Torna alle Sessioni</a>
</div>
@endsection
