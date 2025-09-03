@extends('layouts.app')

@section('title', 'Storico Formazione - ' . $lavoratore->cognome . ' ' . $lavoratore->nome)

@section('content')
<div class="container">
    <h2 class="mb-4">📚 Storico Formazione - {{ $lavoratore->cognome }} {{ $lavoratore->nome }}</h2>

    @if ($formazioni->isEmpty())
        <div class="alert alert-info">Nessuna partecipazione alla formazione trovata.</div>
    @else
        <table class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>Corso</th>
                    <th>Data</th>
                    <th>Durata (ore)</th>
                    <th>Docente</th>
                    <th>Luogo</th>
                    <th>Attestato</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($formazioni as $f)
                    <tr>
                        <td>{{ $f->sessione->corso->titolo }}</td>
                        <td>{{ \Carbon\Carbon::parse($f->data_formazione)->format('d/m/Y') }}</td>
                        <td>{{ $f->sessione->durata_effettiva }}</td>
                        <td>{{ $f->sessione->docente }}</td>
                        <td>{{ $f->sessione->luogo }}</td>
                        <td>{{ ucfirst(str_replace('_', ' ', $f->attestato)) }}</td>
                        <td>
                            @if ($f->link_attestato)
                                <a href="{{ $f->link_attestato }}" target="_blank">📎</a>
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection
