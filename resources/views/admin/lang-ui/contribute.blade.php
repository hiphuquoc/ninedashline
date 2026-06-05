@extends('layouts.admin')

@section('content')
@include('admin.components.pageHeader', [
    'title' => 'Chung sức — đa ngôn ngữ',
    'desc' => 'Tâm thư, 4 bước đồng hành (dùng chung landing), thanh toán &amp; ghi nhận nhà tài trợ',
    'icon' => '<path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/>',
    'actionUrl' => $publicPreviewUrl,
    'actionText' => 'Xem trang Chung sức',
])

@include('admin.lang-ui.partials.localeBanner', [
    'localeEditRoute' => $localeEditRoute,
    'publicPreviewUrl' => $publicPreviewUrl,
    'previewLabel' => 'Xem Chung sức',
    'aiEnabled' => $aiEnabled ?? false,
])

@php
    $headerThemeResolver = fn (string $id) => \App\Support\ContributeLangSections::headerTheme($id);
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
