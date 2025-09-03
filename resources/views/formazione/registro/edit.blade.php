@extends('layouts.app')

@section('title', 'Modifica Partecipazione alla Formazione')

@section('content')
<div class="container">
    <h2 class="mb-4">✏️ Modifica Partecipazione - {{ $formazione->lavoratore->cognome }} {{ $formazione->lavoratore->nome }}</h2>

    <form action="{{ route('formazione.registro.update', $formazione->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="data_formazione" class="form-label">Data Formazione</label>
            <input type="date" name="data_formazione" class="form-control" value="{{ old('data_formazione', $formazione->data_formazione) }}" required>
        </div>

        <div class="mb-3">
            <label for="data_scadenza" class="form-label">Data Scadenza</label>
            <input type="date" name="data_scadenza" class="form-control" value="{{ old('data_scadenza', $formazione->data_scadenza) }}">
            <div class="form-text">Lascia vuoto se il corso non ha scadenza.</div>
        </div>

        <div class="mb-3">
            <label for="attestato" class="form-label">Attestato</label>
            <select name="attestato" class="form-select">
                <option value="non_previsto" {{ old('attestato', $formazione->attestato) == 'non_previsto' ? 'selected' : '' }}>Non previsto</option>
                <option value="presente" {{ old('attestato', $formazione->attestato) == 'presente' ? 'selected' : '' }}>Presente</option>
                <option value="non_presente" {{ old('attestato', $formazione->attestato) == 'non_presente' ? 'selected' : '' }}>Non presente</option>
				<option value="in_attesa" {{ old('attestato', $formazione->attestato) == 'in_attesa' ? 'selected' : '' }}>In attesa</option>
				<option value="verbale_interno" {{ old('attestato', $formazione->attestato) == 'verbale_interno' ? 'selected' : '' }}>Verbale interno</option>
				<option value="attestato_interno" {{ old('attestato', $formazione->attestato) == 'attestato_interno' ? 'selected' : '' }}>Attestato interno</option>
            </select>
        </div>

        <div class="mb-3">
            <label for="link_attestato" class="form-label">Link Attestato</label>
            <input type="url" name="link_attestato" class="form-control" value="{{ old('link_attestato', $formazione->link_attestato) }}">
        </div>

        <button type="submit" class="btn btn-primary">Aggiorna</button>
        <a href="{{ route('formazione.registro.index', $formazione->sessione_id) }}" class="btn btn-secondary">Annulla</a>
    </form>
</div>
@endsection
