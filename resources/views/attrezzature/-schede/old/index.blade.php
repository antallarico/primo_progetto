@extends('layouts.app')

@section('content')
<div class="container py-4">
    <h2>Schede Attrezzature Dinamiche</h2>
    <a href="{{ route('attrezzature.schede.create') }}" class="btn btn-primary mb-3">➕ Nuova Scheda</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>ID</th>
                <th>Tipologia</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            @foreach($schede as $scheda)
                <tr>
                    <td>{{ $scheda->id }}</td>
                    <td>{{ $scheda->tipologia->nome ?? '-' }}</td>
                    <td>
                        <a href="{{ route('attrezzature.schede.edit', $scheda) }}" class="btn btn-sm btn-warning">✏️ Modifica</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

