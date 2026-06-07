@php
  $brandMark = $brandMark ?? \App\Support\BrandMark::data();
  $logoFetchPriority = $logoFetchPriority ?? 'auto';
  $logoLoading = $logoFetchPriority === 'high' ? 'eager' : 'lazy';
@endphp
<span class="brand-mark__inner">
  <img
    class="brand-mark__img"
    src="{{ $brandMark['logo_url'] }}"
    alt="{{ $brandMark['logo_alt'] }}"
    width="120"
    height="40"
    decoding="async"
    loading="{{ $logoLoading }}"
    @if ($logoFetchPriority === 'high') fetchpriority="high" @endif
  >
  <span class="brand-mark__name">{{ $brandMark['name'] }}</span>
</span>
