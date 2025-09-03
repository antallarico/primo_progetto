@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-4">Riepilogo Formazione Lavoratori</h1>

    <table class="table table-bordered table-sm table-hover">
        <thead class="table-light">
            <tr>
                <th>Lavoratore</th>
                <th>Storico Formazione</th>
                @foreach($competenze as $comp)
                    <th>{{ $comp }}</th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @foreach($riepilogo as $id => $dati)
                <tr>
                    <td>{{ $dati['nome'] }}</td>
                    
                    <td><a href="{{ route('formazione.storicolavoratore', $id) }}" class="btn btn-sm btn-outline-primary" target="_blank">Dettaglio</a></td>

                    @foreach($competenze as $comp)
                        @php
                            $info = $dati['competenze'][$comp] ?? null;
                            $stato = $info['stato'] ?? '—';
                            $scadenza = $info['scadenza'] ?? null;
                            $ore_valide = $info['ore_valide'] ?? null;

                            $badgeClass = match ($stato) {
                                'valida' => 'bg-success text-white',
                                'in scadenza' => 'bg-warning text-dark',
                                'scaduta' => 'bg-danger text-white',
                                'non svolta' => 'bg-secondary text-white',
                                default => 'bg-light text-dark',
                            };
                        @endphp
                        <td>
                            @if ($stato === 'non svolta' && ($ore_valide === 0.0 || $ore_valide === 0 || is_null($ore_valide)))
                                <span class="text-muted">n/a</span>
                            @else
                                <span class="badge {{ $badgeClass }}">{{ $stato }}</span><br>
                                @if ($scadenza)
                                    <small>Scad.: {{ \Carbon\Carbon::parse($scadenza)->format('d/m/Y') }}</small><br>
                                @endif
                                @if (!is_null($ore_valide))
                                    <small>Ore: {{ number_format($ore_valide, 1) }}</small><br>

                                    @if (!empty($info['prossima_uscita']))
                                        @php
                                            $giorni = (int) \Carbon\Carbon::now()->diffInDays($info['prossima_uscita'], false);
                                            $classe = $giorni < 0 ? 'danger' : ($giorni <= 60 ? 'warning' : 'info');
                                            $messaggio = $giorni < 0
                                                ? 'Scaduta da ' . abs($giorni) . ' gg'
                                                : 'Scade tra ' . $giorni . ' gg';
                                        @endphp
                                        <span class="badge bg-{{ $classe }}">⏳ {{ $messaggio }}</span>
                                    @endif
                                @endif
                            @endif
                        </td>
                    @endforeach
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection

