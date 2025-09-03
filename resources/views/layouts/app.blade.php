<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Gestione Sicurezza')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            overflow-x: hidden;
        }
        .sidebar {
            height: 100vh;
            position: fixed;
            top: 0;
            left: 0;
            width: 240px;
            background-color: #343a40;
            padding-top: 60px;
        }
        .sidebar a {
            color: #fff;
            padding: 12px 20px;
            display: block;
            text-decoration: none;
            font-size: 15px;
        }
        .sidebar a:hover,
        .sidebar a.active {
            background-color: #495057;
        }
        .sidebar .menu-title {
            color: #adb5bd;
            text-transform: uppercase;
            font-size: 12px;
            padding: 10px 20px 5px;
            font-weight: bold;
        }
        .main-content {
            margin-left: 240px;
            padding: 20px;
        }
    </style>
</head>
<body>

    {{-- Sidebar --}}
    <div class="sidebar">
        <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">🏠 Dashboard</a>

        <div class="menu-title">📚 Formazione</div>
        <a href="{{ url('/formazione/lavoratori') }}" class="{{ request()->is('formazione/lavoratori') ? 'active' : '' }}">👤 Storico Lavoratori</a>
        <a href="{{ url('/formazione/corsi') }}" class="{{ request()->is('formazione/corsi') ? 'active' : '' }}">📘 Archivio Corsi</a>
        <a href="{{ url('/formazione/sessioni') }}" class="{{ request()->is('formazione/sessioni') ? 'active' : '' }}">🗓️ Sessioni Formative</a>
		
		<div class="menu-title">🎯 Addestramenti</div>
		<a href="{{ route('addestramenti.tipologie.index') }}" class="{{ request()->routeIs('addestramenti.tipologie.*') ? 'active' : '' }}">🧩 Tipologie</a>
		<a href="{{ route('addestramenti.registro.index') }}" class="{{ request()->routeIs('addestramenti.registro.*') ? 'active' : '' }}">📒 Registro</a>

        <div class="menu-title">🛠️ Attrezzature</div>
        <a href="{{ url('/attrezzature') }}" class="{{ request()->is('attrezzature') ? 'active' : '' }}">📦 Elenco Attrezzature</a>
        <a href="{{ route('attrezzature.tipologie.index') }}" class="{{ request()->routeIs('attrezzature.tipologie.*') ? 'active' : '' }}">🧩 Tipologie Attrezzature</a>
		<a href="{{ route('attrezzature.quadro') }}" class="{{ request()->routeIs('attrezzature.quadro') ? 'active' : '' }}">📊 Quadro Attrezzature</a>
		
        <div class="menu-title">🔧 Manutenzioni</div>
        <a href="{{ url('/manutenzioni/tipologie') }}" class="{{ request()->is('manutenzioni/tipologie') ? 'active' : '' }}">🛠️ Tipologie</a>
        <a href="{{ url('/manutenzioni/competenze') }}" class="{{ request()->is('manutenzioni/competenze') ? 'active' : '' }}">👤 Competenze</a>
 <!--       <a href="{{ url('/manutenzioni/checklist') }}" class="{{ request()->is('manutenzioni/checklist') ? 'active' : '' }}">✅ Checklist</a> -->
        <a href="{{ url('/manutenzioni/programmate') }}" class="{{ request()->is('manutenzioni/programmate') ? 'active' : '' }}">📅 Programmate</a>
        <a href="{{ url('/manutenzioni/registro') }}" class="{{ request()->is('manutenzioni/registro') ? 'active' : '' }}">📖 Registro</a>

        <div class="menu-title">📐 Modelli Dinamici</div>
        <a href="{{ url('/modelli-dinamici') }}" class="{{ request()->is('modelli-dinamici') ? 'active' : '' }}">📋 Elenco Modelli</a>
        


        <div class="menu-title">👥 Risorse Umane</div>
        <a href="{{ url('/lavoratori') }}" class="{{ request()->is('lavoratori') ? 'active' : '' }}">👷‍♂️ Lavoratori</a>
    </div>

    {{-- Main Content --}}
    <div class="main-content">
        @yield('content')
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
	
@stack('scripts')

</body>
</html>
