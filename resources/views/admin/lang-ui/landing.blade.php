@extends('layouts.admin')

@section('content')
@include('admin.components.pageHeader', [
    'title' => 'Nội dung landing — đa ngôn ngữ',
    'desc' => 'Chỉnh chuỗi UI theo từng section · bản gốc tiếng Việt (vi)',
    'icon' => '<path d="M12 21a9.004 9.004 0 008.716-6.747M12 21a9.004 9.004 0 01-8.716-6.747M12 21c2.485 0 4.5-4.03 4.5-9S14.485 3 12 3m0 18c-2.485 0-4.5-4.03-4.5-9S9.515 3 12 3m0 0a8.997 8.997 0 017.843 4.582M12 3a8.997 8.997 0 00-7.843 4.582m15.686 0A11.953 11.953 0 0112 10.5c-2.998 0-5.74-1.1-7.843-2.918m15.686 0A8.959 8.959 0 0121 12c0 .778-.099 1.533-.284 2.253m0 0A17.919 17.919 0 0112 16.5c-3.162 0-6.133-.815-8.716-2.247m0 0A9.015 9.015 0 013 12c0-1.605.42-3.113 1.157-4.418"/>',
    'actionUrl' => $publicPreviewUrl,
    'actionText' => 'Xem trang công khai',
])

@include('admin.lang-ui.partials.localeBanner', [
    'localeEditRoute' => $localeEditRoute,
    'publicPreviewUrl' => $publicPreviewUrl,
    'previewLabel' => 'Xem landing',
    'aiEnabled' => $aiEnabled ?? false,
])

@php
    $headerThemeResolver = fn (string $id) => \App\Support\LandingLangSections::headerTheme($id);
@endphp

@include('admin.lang-ui.partials.editor', compact(
    'sections',
    'locale',
    'isMaster',
    'saveUrl',
    'headerThemeResolver',
    'aiEnabled',
    'aiTranslateUrl',
    'aiTranslateSectionUrl',
    'aiTranslateHorizontalUrl',
    'aiConfigUrl',
    'googleTranslateUrl',
    'exportPromptUrl',
    'importUrl',
    'scope',
    'horizontalTargetLocales'
))

@if ($isMaster)
    @unless ($aiEnabled ?? false)
        @include('admin.lang-ui.partials.ai-disabled-notice')
    @endunless
    @include('admin.lang-ui.partials.horizontal-translate-modals')
@else
    @unless ($aiEnabled ?? false)
        @include('admin.lang-ui.partials.ai-disabled-notice')
    @endunless
    @include('admin.lang-ui.partials.translate-modals')
@endif

@include('admin.lang-ui.partials.scripts', compact('isMaster'))
@if ($isMaster)
    @include('admin.lang-ui.partials.horizontal-translate-scripts')
@else
    @include('admin.lang-ui.partials.translate-scripts')
@endif
@endsection
