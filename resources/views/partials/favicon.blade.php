@php($faviconUrl = \App\Support\BrandMark::faviconUrl())
<link rel="preload" as="image" href="{{ $faviconUrl }}" type="image/png">
<link rel="icon" href="{{ $faviconUrl }}" type="image/png" sizes="32x32">
<link rel="apple-touch-icon" href="{{ $faviconUrl }}">
