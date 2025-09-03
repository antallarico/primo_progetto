@extends('layouts.app')

@section('title', 'Aggiungi Partecipante alla Sessione')

@section('content')
<div class="container">
    <h2 class="mb-4">➕ Aggiungi Partecipante - Sessione del {{ $sessione->data_sessione }}</h2>

    <form action="{{ route('formazione.registro.store', $sessione->id) }}" method="POST">
        @csrf

        <div class="mb-3">
            <label for="lavoratore_id" class="form-label">Lavoratore</label>
            <select name="lavoratore_id" class="form-select" required>
                <option value="">-- Seleziona --</option>
                @foreach ($lavoratori as $lavoratore)
                    <option value="{{ $lavoratore->id }}" {{ old('lavoratore_id') == $lavoratore->id ? 'selected' : '' }}>
                        {{ $lavoratore->cognome }} {{ $lavoratore->nome }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="data_formazione" class="form-label">Data Formazione</label>
            <input type="date" name="data_formazione" class="form-control" value="{{ old('data_formazione', $sessione->data_sessione) }}" required>
        </div>

        <div class="mb-3">
            <label for="data_scadenza" class="form-label">Data Scadenza</label>
            <input type="date" name="data_scadenza" class="form-control" value="{{ old('data_scadenza') }}">
            <div class="form-text">Lascia vuoto se il corso non ha scadenza.</div>
        </div>

        <div class="mb-3">
            <label for="attestato" class="form-label">Attestato</label>
            <select name="attestato" class="form-select">
                <option value="non_previsto" {{ old('attestato') == 'non_previsto' ? 'selected' : '' }}>Non previsto</option>
                <option value="presente" {{ old('attestato') == 'presente' ? 'selected' : '' }}>Presente</option>
                <option value="non_presente" {{ old('attestato') == 'non_presente' ? 'selected' : '' }}>Non presente</option>
				<option value="in_attesa" {{ old('attestato') == 'in_attesa' ? 'selected' : '' }}>In attesa</option>
				<option value="verbale_interno" {{ old('attestato') == 'verbale_interno' ? 'selected' : '' }}>Verbale interno</option>
				<option value="attestato_interno" {{ old('attestato') == 'attestato_interno' ? 'selected' : '' }}>Attestato interno</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="link_attestato" class="form-label">Link Attestato</label>
            <input type="url" name="link_attestato" class="form-control" value="{{ old('link_attestato') }}">
        </div>

        <button type="submit" class="btn btn-primary">Salva</button>
        <a href="{{ route('formazione.registro.index', $sessione->id) }}" class="btn btn-secondary">Annulla</a>
    </form>
</div>
@endsection
