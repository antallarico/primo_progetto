{{-- resources/views/components/dynform/filter.blade.php --}}
@props([
  // selettori (identici a quelli usati nei moduli)
  'tipSelect'   => '#tipologia_id',
  'modelSelect' => '#modello_id',

  // opzionale: passa i modelli da server (iniettiamo window.modelliDinamici)
  'modelli' => null,

  // log in console
  'debug' => false,
])

@if(!is_null($modelli))
  <script>window.modelliDinamici = @json($modelli);</script>
@endif

<div
  data-dynform="filter-tipologia-modello"
  data-tip-select="{{ $tipSelect }}"
  data-model-select="{{ $modelSelect }}"
  @if($debug) data-debug="1" @endif
></div>

@pushOnce('scripts')
  <script src="{{ asset('js/dynform/filter-tipologia-modello.js') }}?v={{ @filemtime(public_path('js/dynform/filter-tipologia-modello.js')) }}"></script>
@endPushOnce
