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
		.sidebar .menu-toggle{
  width:100%; text-align:left; background:none; border:0; color:#fff;
  padding:12px 20px; font-size:15px; display:flex; align-items:center; justify-content:space-between;
}
.sidebar .menu-toggle:hover, .sidebar .menu-toggle[aria-expanded="true"]{
  background-color:#495057;
}
.sidebar .chev{
  display:inline-block; transition: transform .15s ease;
  width:0; height:0; border-left:5px solid transparent; border-right:5px solid transparent; border-top:6px solid #adb5bd;
}
.sidebar .menu-toggle[aria-expanded="true"] .chev{ transform: rotate(180deg); }
.sidebar .collapse a{ padding-left:34px; } /* indent voci figlie */

    </style>
</head>
<body>

    {{-- Sidebar --}}
<div class="sidebar">
  <a href="{{ url('/') }}" class="{{ request()->is('/') ? 'active' : '' }}">🏠 Dashboard</a>

  {{-- ================= DPI ================= --}}
  @php $openDpi = request()->routeIs('dpi.*'); @endphp
  <button class="menu-toggle" data-bs-toggle="collapse" data-bs-target="#menuDPI"
          aria-expanded="{{ $openDpi ? 'true' : 'false' }}" aria-controls="menuDPI">
    🦺 DPI <span class="chev"></span>
  </button>
  <div id="menuDPI" class="collapse {{ $openDpi ? 'show' : '' }}">
    <a href="{{ route('dpi.tipi.index') }}" class="{{ request()->routeIs('dpi.tipi.*') ? 'active' : '' }}">📂 Tipi</a>
    <a href="{{ route('dpi.articoli.index') }}" class="{{ request()->routeIs('dpi.articoli.*') ? 'active' : '' }}">📦 Articoli</a>
    <a href="{{ route('dpi.consegne.index') }}" class="{{ request()->routeIs('dpi.consegne.*') ? 'active' : '' }}">📑 Consegne</a>
  </div>

  {{-- ============== Formazione ============== --}}
  @php $openForm = request()->is('formazione/*'); @endphp
  <button class="menu-toggle" data-bs-toggle="collapse" data-bs-target="#menuFormazione"
          aria-expanded="{{ $openForm ? 'true' : 'false' }}" aria-controls="menuFormazione">
    📚 Formazione <span class="chev"></span>
  </button>
  <div id="menuFormazione" class="collapse {{ $openForm ? 'show' : '' }}">
    <a href="{{ url('/formazione/lavoratori') }}" class="{{ request()->is('formazione/lavoratori') ? 'active' : '' }}">👤 Storico Lavoratori</a>
    <a href="{{ url('/formazione/corsi') }}" class="{{ request()->is('formazione/corsi') ? 'active' : '' }}">📘 Archivio Corsi</a>
    <a href="{{ url('/formazione/sessioni') }}" class="{{ request()->is('formazione/sessioni') ? 'active' : '' }}">🗓️ Sessioni Formative</a>
  </div>

  {{-- ============ Addestramenti ============ --}}
  @php $openAdd = request()->routeIs('addestramenti.*'); @endphp
  <button class="menu-toggle" data-bs-toggle="collapse" data-bs-target="#menuAddestramenti"
          aria-expanded="{{ $openAdd ? 'true' : 'false' }}" aria-controls="menuAddestramenti">
    🎯 Addestramenti <span class="chev"></span>
  </button>
  <div id="menuAddestramenti" class="collapse {{ $openAdd ? 'show' : '' }}">
    <a href="{{ route('addestramenti.tipologie.index') }}" class="{{ request()->routeIs('addestramenti.tipologie.*') ? 'active' : '' }}">🧩 Tipologie</a>
    <a href="{{ route('addestramenti.registro.index') }}" class="{{ request()->routeIs('addestramenti.registro.*') ? 'active' : '' }}">📒 Registro</a>
  </div>

  {{-- ============ Attrezzature ============ --}}
  @php $openAtt = request()->is('attrezzature*'); @endphp
  <button class="menu-toggle" data-bs-toggle="collapse" data-bs-target="#menuAttrezzature"
          aria-expanded="{{ $openAtt ? 'true' : 'false' }}" aria-controls="menuAttrezzature">
    🛠️ Attrezzature <span class="chev"></span>
  </button>
  <div id="menuAttrezzature" class="collapse {{ $openAtt ? 'show' : '' }}">
    <a href="{{ url('/attrezzature') }}" class="{{ request()->is('attrezzature') ? 'active' : '' }}">📦 Elenco Attrezzature</a>
    <a href="{{ route('attrezzature.tipologie.index') }}" class="{{ request()->routeIs('attrezzature.tipologie.*') ? 'active' : '' }}">🧩 Tipologie Attrezzature</a>
    <a href="{{ route('attrezzature.quadro') }}" class="{{ request()->routeIs('attrezzature.quadro') ? 'active' : '' }}">📊 Quadro Attrezzature</a>
  </div>

  {{-- ============ Manutenzioni ============ --}}
  @php $openMan = request()->is('manutenzioni/*'); @endphp
  <button class="menu-toggle" data-bs-toggle="collapse" data-bs-target="#menuManutenzioni"
          aria-expanded="{{ $openMan ? 'true' : 'false' }}" aria-controls="menuManutenzioni">
    🔧 Manutenzioni <span class="chev"></span>
  </button>
  <div id="menuManutenzioni" class="collapse {{ $openMan ? 'show' : '' }}">
    <a href="{{ url('/manutenzioni/tipologie') }}" class="{{ request()->is('manutenzioni/tipologie') ? 'active' : '' }}">🛠️ Tipologie</a>
    <a href="{{ url('/manutenzioni/competenze') }}" class="{{ request()->is('manutenzioni/competenze') ? 'active' : '' }}">👤 Competenze</a>
    <a href="{{ url('/manutenzioni/programmate') }}" class="{{ request()->is('manutenzioni/programmate') ? 'active' : '' }}">📅 Programmate</a>
    <a href="{{ url('/manutenzioni/registro') }}" class="{{ request()->is('manutenzioni/registro') ? 'active' : '' }}">📖 Registro</a>
  </div>

	{{-- ======== Prodotti Chimici ======== --}}
	@php $openChem = request()->routeIs('chimica.*'); @endphp
	<button class="menu-toggle" data-bs-toggle="collapse" data-bs-target="#menuChimica"
        aria-expanded="{{ $openChem ? 'true' : 'false' }}" aria-controls="menuChimica">
		🧪 Sostanze chimiche <span class="chev"></span>
	</button>
	<div id="menuChimica" class="collapse {{ $openChem ? 'show' : '' }}">
		<a href="{{ route('chimica.prodotti.index') }}" class="{{ request()->routeIs('chimica.prodotti.*') ? 'active' : '' }}">📦 Catalogo</a>
	</div>
	
  {{-- ========= Modelli Dinamici ========= --}}
  @php $openMod = request()->is('modelli-dinamici'); @endphp
  <button class="menu-toggle" data-bs-toggle="collapse" data-bs-target="#menuModelli"
          aria-expanded="{{ $openMod ? 'true' : 'false' }}" aria-controls="menuModelli">
    📐 Modelli Dinamici <span class="chev"></span>
  </button>
  <div id="menuModelli" class="collapse {{ $openMod ? 'show' : '' }}">
    <a href="{{ url('/modelli-dinamici') }}" class="{{ request()->is('modelli-dinamici') ? 'active' : '' }}">📋 Elenco Modelli</a>
  </div>

  {{-- ========= Risorse Umane ========= --}}
  @php $openHR = request()->is('lavoratori'); @endphp
  <button class="menu-toggle" data-bs-toggle="collapse" data-bs-target="#menuHR"
          aria-expanded="{{ $openHR ? 'true' : 'false' }}" aria-controls="menuHR">
    👥 Risorse Umane <span class="chev"></span>
  </button>
  <div id="menuHR" class="collapse {{ $openHR ? 'show' : '' }}">
    <a href="{{ url('/lavoratori') }}" class="{{ request()->is('lavoratori') ? 'active' : '' }}">👷‍♂️ Lavoratori</a>
  </div>
</div>


    {{-- Main Content --}}
    <div class="main-content">
        @yield('content')
    </div>

    {{-- Bootstrap JS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

<script>
document.querySelectorAll('.sidebar .menu-toggle').forEach(btn => {
  const target = document.querySelector(btn.dataset.bsTarget);
  const id = target?.id;
  if(!id) return;

  // ripristina stato
  const saved = localStorage.getItem('menu:'+id);
  if(saved === '1' && !target.classList.contains('show')){
    new bootstrap.Collapse(target, {toggle:true});
    btn.setAttribute('aria-expanded','true');
  }

  target.addEventListener('shown.bs.collapse', () => localStorage.setItem('menu:'+id,'1'));
  target.addEventListener('hidden.bs.collapse', () => localStorage.setItem('menu:'+id,'0'));
});
</script>

	
@stack('scripts')

</body>
</html>
