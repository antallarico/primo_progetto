@extends('layouts.app')

@section('title', 'Lavoratori - Storico Formazione')

@section('content')
<div class="container">
    <h2 class="mb-4">👷‍♂️ Elenco Lavoratori - Storico Formazione</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-light">
            <tr>
                <th>Cognome</th>
                <th>Nome</th>
                <th>Storico Formazione</th>
                @foreach($anni as $anno)
                    <th>{{ $anno }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($lavoratori as $lavoratore)
                <tr>
                    <td>{{ $lavoratore->cognome }}</td>
                    <td>{{ $lavoratore->nome }}</td>
                    <td>
                        <a href="{{ url('/formazione/storicolavoratore/' . $lavoratore->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                            📄 Visualizza
                        </a>
						<a href="{{ route('formazione.storicolavoratore', $lavoratore->id) }}" class="btn btn-sm btn-primary" target="_blank">Dettaglio</a>
                    </td>
                    @foreach($anni as $anno)
                        <td>{{ $formazioni[$lavoratore->id][$anno] ?? '0' }}</td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

