<?php

namespace App\Support;

/**
 * URL locale kiểu Hitour: locale mặc định không prefix, các locale khác /{code}.
 * Mặc định site: en → /. Tiếng Việt → /vi.
 */
final class LocaleUrl
{
    public static function defaultCode(): string
    {
        return LanguageRegistry::defaultCode();
    }

    public static function isDefault(string $locale): bool
    {
        return strtolower($locale) === self::defaultCode();
    }

    /**
     * Đường dẫn trang chủ theo locale (luôn bắt đầu bằng /).
     */
    public static function home(string $locale): string
    {
        $locale = strtolower(trim($locale));

        return self::isDefault($locale) ? '/' : '/' . rawurlencode($locale);
    }

    /**
     * Pattern cho Route::where('locale', …) — mọi code active trừ default.
     */
    public static function routeLocalePattern(): string
    {
        $codes = [];
        foreach (LanguageRegistry::list() as $code => $row) {
            if (!($row['is_active'] ?? true)) {
                continue;
            }
            if (self::isDefault($code)) {
                continue;
            }
            $codes[] = preg_quote($code, '#');
        }

        return $codes !== [] ? implode('|', $codes) : 'vi';
    }

    /**
     * Locale từ URL (segment / route), không dùng cookie.
     */
    public static function localeFromRequest(\Illuminate\Http\Request $request): string
    {
        $param = $request->route('locale');
        if (is_string($param) && $param !== '') {
            return strtolower($param);
        }

        $first = $request->segment(1);
        if (is_string($first) && $first !== '' && isset(LanguageRegistry::list()[strtolower($first)])) {
            return strtolower($first);
        }

        return self::defaultCode();
    }

    /**
     * Locale dùng cho nội dung (lang_ui) — fallback nếu chưa có bản dịch.
     */
    public static function contentLocale(string $urlLocale): string
    {
        return LangUi::hasLocale($urlLocale) ? $urlLocale : self::defaultCode();
    }

    /**
     * @return array<string, string> code → path
     */
    public static function homePathsMap(): array
    {
        $map = [];
        foreach (LanguageRegistry::list() as $code => $row) {
            if (!($row['is_active'] ?? true)) {
                continue;
            }
            $map[$code] = self::home($code);
        }

        return $map;
    }
}
