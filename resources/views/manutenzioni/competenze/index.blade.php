@extends('layouts.app')

@section('content')
<div class="container py-3">
    <h2>Competenze / Manutentori</h2>

    <a href="{{ route('manutenzioni.competenze.create') }}" class="btn btn-primary mb-3">➕ Nuova Competenza</a>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered table-sm">
        <thead>
            <tr>
                <th>Nome</th>
                <th>Tipo</th>
                <th>Contatti</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($competenze as $c)
                <tr>
                    <td>{{ $c->nome }}</td>
                    <td>{{ ucfirst($c->tipo) }}</td>
                    <td>{{ $c->contatti }}</td>
                    <td>
                        <a href="{{ route('manutenzioni.competenze.edit', $c->id) }}" class="btn btn-sm btn-secondary">✏️</a>
                        <form action="{{ route('manutenzioni.competenze.destroy', $c->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Eliminare questa competenza?')">
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
