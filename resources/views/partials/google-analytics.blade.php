@php
    $gaMeasurementId = trim((string) config('services.google_analytics.measurement_id', ''));
@endphp
@if ($gaMeasurementId !== '')
<link rel="preconnect" href="https://www.googletagmanager.com" crossorigin>
<script async src="https://www.googletagmanager.com/gtag/js?id={{ $gaMeasurementId }}"></script>
<script>
  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());
  gtag('config', @json($gaMeasurementId));
</script>
@endif
