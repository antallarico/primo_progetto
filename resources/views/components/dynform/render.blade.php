{{-- resources/views/components/dynform/render.blade.php --}}
@props([
  'schema' => [],    // array (schema_json.fields)
  'layout' => null,  // array (layout_json) oppure null
  'valori' => [],    // payload_json (array)
  'mode'   => 'embed' // 'embed' | 'full'
])

@php($formId = 'dynform_'.uniqid())

<div id="{{ $formId }}"
     class="dynform-v2 {{ $mode === 'full' ? 'dynform-full' : 'dynform-embed' }}"
     data-schema='@json($schema)'
     data-layout='@json($layout)'
     data-values='@json($valori)'
     data-mode='{{ $mode }}'>
</div>

@pushOnce('scripts')
  {{-- carica i core centralizzati (dynamic-form + mount-by-select) --}}
  @include('partials.dynform-core')
@endPushOnce

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  var el = document.getElementById(@json($formId));
  if (!el) return;

  // Preferisci l'API moderna DynForm.renderInto; fallback al nome legacy se presente
  var DF = window.DynForm || {};
  if (typeof DF.renderInto === 'function') {
    try {
      const schema = JSON.parse(el.dataset.schema || '{}') || {};
      const layout = JSON.parse(el.dataset.layout || 'null');
      const values = JSON.parse(el.dataset.values || '{}') || {};
      const mode   = el.dataset.mode || 'embed';
      DF.renderInto(el, schema, layout, values, mode);
      return;
    } catch(e) { console.error('dynform: parse error', e); }
  }
  if (typeof window.renderDynamicFormV2 === 'function') {
    window.renderDynamicFormV2(el);
  }
});
</script>
@endpush

