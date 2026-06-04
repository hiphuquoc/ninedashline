<!DOCTYPE html>
<html lang="{{ $htmlLang }}" dir="{{ page_text_dir($urlLocale ?? $locale) }}">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
@include('partials.favicon')
<meta name="description" content="{{ $metaDescription }}">
@include('partials.seo-head')
<title>{{ $metaTitle }}</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="preconnect" href="https://flagcdn.com" crossorigin>
<link href="{{ $googleFontsUrl }}" rel="stylesheet">
@vite(['resources/css/landing-fab.css', 'resources/css/landing-lang-switcher.css', 'resources/js/landing-i18n.js'])
@stack('head')
</head>
<body>
<script id="landing-config" type="application/json">{!! json_encode($landingScriptConfig, JSON_HEX_TAG | JSON_HEX_APOS | JSON_HEX_AMP | JSON_UNESCAPED_UNICODE) !!}</script>
@yield('content')
@stack('scripts')
</body>
</html>
