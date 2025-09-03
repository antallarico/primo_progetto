{{-- resources/views/components/dynform/mount.blade.php --}}
@props([
  // selettore del <select> che contiene l’ID del modello
  'select' => '[name="modello_id"]',

  // base dell’endpoint JSON (es: /modelli-dinamici) oppure template custom con __ID__
  'base' => null,              // es. url('modelli-dinamici')
  'endpoint' => null,          // es. '/modelli-dinamici/__ID__/json'

  // modalità di render
  'mode' => 'embed',

  // valori iniziali (payload) da precompilare in EDIT
  'values' => [],

  // log in console
  'debug' => false,

  // opzionali per SSR di cortesia
  'modello' => null,
  'compilazione' => null,

  // opzionale: id iniziale del modello (se non passato lo auto-detecta dalla select)
  'initialId' => null,
])

@php
  $base = $base ? rtrim($base, '/') : url('modelli-dinamici');
  $initialId = $initialId ?: ($modello->id ?? null);
@endphp

<div
  data-dynform="mount-by-select"
  data-select="{{ $select }}"
  data-base="{{ $base }}"
  @if($endpoint) data-endpoint="{{ $endpoint }}" @endif
  data-mode="{{ $mode }}"
  data-values='@json($values)'
  @if($debug) data-debug="1" @endif
  @if($initialId) data-initial-id="{{ $initialId }}" @endif
>
  {{-- SSR di cortesia: mostra subito il form se abbiamo schema/layout (poi il JS lo rimpiazza) --}}
  @if($modello && $modello->schema_json && $modello->layout_json)
    <x-dynform.render
      :schema="$modello->schema_json"
      :layout="$modello->layout_json"
      :valori="$compilazione->payload_json ?? []"
      :mode="$mode" />
  @endif
</div>

@pushOnce('scripts')
  @include('partials.dynform-core')
@endPushOnce


