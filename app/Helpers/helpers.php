<?php

use App\Support\LanguageRegistry;

if (!function_exists('url_locale')) {
    /** Locale từ URL (/vi, /ja, …) — dùng cho switcher & canonical. */
    function url_locale(): string
    {
        if (app()->bound('request')) {
            $fromRequest = request()->attributes->get('url_locale');
            if (is_string($fromRequest) && $fromRequest !== '') {
                return $fromRequest;
            }
        }

        return (string) config('language.default_code', 'en');
    }
}

if (!function_exists('current_locale')) {
    /** Locale nội dung (lang_ui) — có thể khác url_locale nếu chưa có bản dịch. */
    function current_locale(): string
    {
        return (string) (app()->getLocale() ?: config('language.default_code', 'en'));
    }
}

if (!function_exists('t')) {
    /**
     * Dịch chuỗi UI từ config/lang_ui/{locale}/*.php (locale hiện tại → fallback).
     */
    function t(string $key, array $replace = []): string
    {
        $locale = current_locale();
        $fallback = (string) config('language.fallback_code', 'vi');

        static $bundles = [];

        if (! isset($bundles[$locale])) {
            $bundles[$locale] = \App\Support\LangUi::forLocale($locale);
        }

        $value = $bundles[$locale][$key] ?? null;

        if ($value === null && $fallback !== $locale) {
            if (! isset($bundles[$fallback])) {
                $bundles[$fallback] = \App\Support\LangUi::forLocale($fallback);
            }
            $value = $bundles[$fallback][$key] ?? null;
        }

        if ($value === null) {
            return $key;
        }

        if (! is_string($value)) {
            $value = (string) $value;
        }

        if ($replace !== []) {
            $search = [];
            $replaceVals = [];
            foreach ($replace as $k => $v) {
                $search[] = ':' . $k;
                $replaceVals[] = is_scalar($v) || $v === null ? (string) ($v ?? '') : (string) json_encode($v, JSON_UNESCAPED_UNICODE);
            }
            $value = str_replace($search, $replaceVals, $value);
        }

        return $value;
    }
}

if (!function_exists('te')) {
    /** Chuỗi plain text — escape HTML (dùng trong attribute, alt, aria-label). */
    function te(string $key, array $replace = []): string
    {
        return e(t($key, $replace));
    }
}

if (!function_exists('th')) {
    /** Chuỗi có thể chứa HTML tin cậy từ lang_ui — dùng với {!! th('key') !!}. */
    function th(string $key, array $replace = []): string
    {
        return t($key, $replace);
    }
}

if (!function_exists('html_lang_attr')) {
    function html_lang_attr(?string $locale = null): string
    {
        return LanguageRegistry::htmlLang($locale ?? current_locale());
    }
}

if (!function_exists('geo_names')) {
    /**
     * @return array{vietnam: string, hoang_sa: string, truong_sa: string, combined: string, nine_dash_line: string, china: string}
     */
    function geo_names(?string $locale = null): array
    {
        return \App\Support\GeoNames::forLocale($locale);
    }
}

if (!function_exists('page_text_dir')) {
    /** Hướng văn bản trang theo locale URL (ar, he, fa, ur → rtl). */
    function page_text_dir(?string $locale = null): string
    {
        $locale = $locale ?? url_locale();

        return LanguageRegistry::textDir($locale);
    }
}
