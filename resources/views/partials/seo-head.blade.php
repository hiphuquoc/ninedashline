@php
  $seoRobots = $seoRobots ?? ($robots ?? 'index, follow');
  $seoOgType = $seoOgType ?? 'website';
  $seoOgImage = $seoOgImage ?? ($ogImage ?? null);
  $seoTwitterCard = $seoTwitterCard ?? 'summary_large_image';
  $seoHreflang = $seoHreflang ?? ($hreflang ?? []);
@endphp
<meta name="robots" content="{{ $seoRobots }}">
@if(!empty($canonicalUrl))
<link rel="canonical" href="{{ $canonicalUrl }}">
@endif
<meta property="og:type" content="{{ $seoOgType }}">
@if(!empty($canonicalUrl))
<meta property="og:url" content="{{ $canonicalUrl }}">
@endif
<meta property="og:title" content="{{ $metaOgTitle }}">
<meta property="og:description" content="{{ $metaOgDescription }}">
@if(!empty($seoOgImage))
<meta property="og:image" content="{{ $seoOgImage }}">
<meta property="og:image:alt" content="{{ $metaOgTitle }}">
@endif
<meta name="twitter:card" content="{{ $seoTwitterCard }}">
<meta name="twitter:title" content="{{ $metaOgTitle }}">
<meta name="twitter:description" content="{{ $metaOgDescription }}">
@if(!empty($seoOgImage))
<meta name="twitter:image" content="{{ $seoOgImage }}">
@endif
@foreach ($seoHreflang as $alt)
<link rel="alternate" hreflang="{{ $alt['locale'] }}" href="{{ $alt['href'] }}">
@endforeach
@if(!empty($seoDefaultHref))
<link rel="alternate" hreflang="x-default" href="{{ $seoDefaultHref }}">
@endif
