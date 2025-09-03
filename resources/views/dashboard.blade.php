@extends('layouts.app')

@section('title', 'Dashboard')

@section('content')
    <div class="container">
        <h1 class="mb-4">Dashboard</h1>

        <div class="row g-4">
            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">👷 Lavoratori</h5>
                        <p class="card-text">Gestisci l'elenco dei lavoratori.</p>
                        <a href="{{ url('/lavoratori') }}" class="btn btn-primary">Vai</a>
                    </div>
                </div>
            </div>

            <div class="col-md-4">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <h5 class="card-title">🛠️ Attrezzature</h5>
                        <p class="card-text">Anagrafica e gestione attrezzature.</p>
                        <a href="{{ route('attrezzature.index') }}" class="btn btn-primary">Vai</a>
                    </div>
                </div>
            </div>

            {{-- Qui aggiungeremo altri moduli --}}

        </div>
    </div>
@endsection
