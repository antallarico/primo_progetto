@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Storico Corso: {{ $corso->titolo }}</h2>

    @if ($sessioni->isEmpty())
        <div class="alert alert-warning">Nessuna sessione trovata per questo corso.</div>
    @else
        <table class="table table-bordered table-striped mt-3">
            <thead>
                <tr>
                    <th>Lavoratore</th>
                    <th>Data Formazione</th>
                    <th>Data Scadenza</th>
                    <th>Docente</th>
                    <th>Luogo</th>
                    <th>Attestato</th>
                    <th>Link</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($sessioni as $sessione)
                    @foreach ($sessione->partecipazioni as $partecipazione)
                        <tr>
                            <td>{{ $partecipazione->lavoratore->nome }} {{ $partecipazione->lavoratore->cognome }}</td>
                            <td>{{ \Carbon\Carbon::parse($partecipazione->data_formazione)->format('d/m/Y') }}</td>
                            <td>
                                @if ($partecipazione->data_scadenza)
                                    {{ \Carbon\Carbon::parse($partecipazione->data_scadenza)->format('d/m/Y') }}
                                @else
                                    —
                                @endif
                            </td>
                            <td>{{ $sessione->docente }}</td>
                            <td>{{ $sessione->luogo }}</td>
                            <td>{{ $partecipazione->attestato }}</td>
                            <td>
                                @if ($partecipazione->link_attestato)
                                    <a href="{{ $partecipazione->link_attestato }}" target="_blank">🔗</a>
                                @else
                                    —
                                @endif
                            </td>
                        </tr>
                    @endforeach
                @endforeach
            </tbody>
        </table>
    @endif
</div>
@endsection


