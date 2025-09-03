@pushOnce('scripts')
  <script src="{{ asset('js/dynform/dynamic-form.js') }}?v={{ @filemtime(public_path('js/dynform/dynamic-form.js')) }}"></script>
  <script src="{{ asset('js/dynform/mount-by-select.js') }}?v={{ @filemtime(public_path('js/dynform/mount-by-select.js')) }}"></script>
@endPushOnce

