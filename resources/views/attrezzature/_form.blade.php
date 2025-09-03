@php
    $v = fn($field, $default = null) => old($field, isset($attrezzatura) ? ($attrezzatura->$field ?? $default) : $default);

    // ⬇️ identico alla full page: preferisci old() poi $modello->id, poi eventuale colonna attrezzatura->modello_id
    $mid = old('modello_id', $modello->id ?? ($attrezzatura->modello_id ?? null));

    // payload precompilato: preferisci old('payload') poi compilazione corrente
    $values = old('payload', $compilazione->payload_json ?? []);

    // il mount fa SSR di cortesia se gli passi $modello
@endphp


<div class="row g-3">
    <div class="col-md-6">
        <label for="nome" class="form-label">Nome *</label>
        <input type="text" name="nome" id="nome" class="form-control" required value="{{ $v('nome') }}">
        @error('nome')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label for="marca" class="form-label">Marca</label>
        <input type="text" name="marca" id="marca" class="form-control" value="{{ $v('marca') }}">
        @error('marca')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label for="modello" class="form-label">Modello</label>
        <input type="text" name="modello" id="modello" class="form-control" value="{{ $v('modello') }}">
        @error('modello')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
		<label for="matricola" class="form-label">Matricola (fabbricante)</label>
		<input type="text" name="matricola" id="matricola" class="form-control" value="{{ $v('matricola') }}">       
        @error('matricola')<div class="text-danger small">{{ $message }}</div>@enderror		
    </div> 

	<div class="col-md-3">
		<label for="matricola_azienda" class="form-label">Matricola aziendale</label>
		<input type="text" name="matricola_azienda" id="matricola_azienda" class="form-control"
			placeholder="es. ASSET-2024-001"
			value="{{ $v('matricola_azienda') }}">
			@error('matricola_azienda')<div class="text-danger small">{{ $message }}</div>@enderror
	</div>
	
    <div class="col-md-3">
        <label for="data_fabbricazione" class="form-label">Data fabbricazione</label>
        <input type="date" name="data_fabbricazione" id="data_fabbricazione" class="form-control" value="{{ $v('data_fabbricazione') }}">
        @error('data_fabbricazione')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label for="ubicazione" class="form-label">Ubicazione</label>
        <input type="text" name="ubicazione" id="ubicazione" class="form-control" value="{{ $v('ubicazione') }}">
        @error('ubicazione')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-3">
        <label for="stato" class="form-label">Stato *</label>
        <select name="stato" id="stato" class="form-select" required>
            @php $stato = $v('stato', 'in uso'); @endphp
            <option value="in uso"    @selected($stato==='in uso')>In uso</option>
            <option value="fuori uso" @selected($stato==='fuori uso')>Fuori uso</option>
            <option value="dismessa"  @selected($stato==='dismessa')>Dismessa</option>
        </select>
        @error('stato')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        <label for="note" class="form-label">Note</label>
        <textarea name="note" id="note" rows="2" class="form-control">{{ $v('note') }}</textarea>
        @error('note')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="categoria_id" class="form-label">Categoria</label>
        <select name="categoria_id" id="categoria_id" class="form-select">
            <option value="">—</option>
            @foreach($categorie as $c)
                <option value="{{ $c->id }}" @selected($v('categoria_id')==$c->id)>{{ $c->nome }}</option>
            @endforeach
        </select>
        @error('categoria_id')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="attrezzatura_padre_id" class="form-label">Appartiene a (Padre)</label>
        <select name="attrezzatura_padre_id" id="attrezzatura_padre_id" class="form-select">
            <option value="">—</option>
            @foreach($padri as $p)
                <option value="{{ $p->id }}" @selected($v('attrezzatura_padre_id')==$p->id)>{{ $p->nome }}</option>
            @endforeach
        </select>
        @error('attrezzatura_padre_id')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="tipologia_id" class="form-label">Tipologia</label>
        <select name="tipologia_id" id="tipologia_id" class="form-select">
            <option value="">—</option>
            @foreach($tipologie as $t)
                <option value="{{ $t->id }}" @selected($v('tipologia_id')==$t->id)>{{ $t->nome }}</option>
            @endforeach
        </select>
		{{-- Filtro condiviso (Tipologia → Modello) --}}
		<x-dynform.filter :modelli="$modelli" tipSelect="#tipologia_id" modelSelect="#modello_id" />

        @error('tipologia_id')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="tipo" class="form-label">Tipo (libero)</label>
        <input type="text" name="tipo" id="tipo" class="form-control" value="{{ $v('tipo') }}">
        @error('tipo')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-md-4">
        <label for="dich_ce" class="form-label">Dichiarazione CE</label>
        <select name="dich_ce" id="dich_ce" class="form-select">
            @php $dce = old('dich_ce', isset($attrezzatura) ? (is_null($attrezzatura->dich_ce) ? '' : ($attrezzatura->dich_ce ? '1' : '0')) : ''); @endphp
            <option value="">—</option>
            <option value="1" @selected($dce==='1')>Presente</option>
            <option value="0" @selected($dce==='0')>Assente</option>
        </select>
        @error('dich_ce')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    {{-- ========== DynForm: selezione Modello + mount component (come nel Registro) ========== --}}
    <div class="col-md-4">
        <label for="modello_id" class="form-label">Scheda/Modello dinamico</label>
        <select name="modello_id" id="modello_id" class="form-select">
            <option value="">— Nessuno —</option>
        <!--      @php $midSelect = $mid; @endphp -->
            @foreach($modelli as $m)
				<option value="{{ $m->id }}" @selected((string)$mid === (string)$m->id)>{{ $m->nome }}</option>
            <!--    <option value="{{ $m->id }}" @selected($midSelect==$m->id)>{{ $m->nome }}</option> -->
            @endforeach
        </select>
        @error('modello_id')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>

    <div class="col-12">
        {{-- Il componente si occupa di caricare i JS core, fetch del modello e render.
             Nessun JS inline necessario. --}}
        <x-dynform.mount
            select="#modello_id"
            :values="$values"
            :modello="$modello ?? null"
            :compilazione="$compilazione ?? null"
            mode="embed"
        />
        @error('payload')<div class="text-danger small">{{ $message }}</div>@enderror
    </div>
</div>
