<?php

declare(strict_types=1);

namespace App\Support;

/**
 * SEO — canonical, Open Graph, hreflang.
 */
final class SeoMeta
{
    public static function siteUrl(): string
    {
        return rtrim((string) config('project.site_url', config('app.url', 'https://ninedashline.dev')), '/');
    }

    public static function ogImageUrl(): string
    {
        $path = (string) config('seo.og_image', '/storage/images/dinh-nghia-duong-luoi-bo-nine-dash-line.png');

        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        return self::siteUrl() . '/' . ltrim($path, '/');
    }

    /**
     * @return array<string, mixed>
     */
    public static function landing(string $urlLocale): array
    {
        $canonicalPath = LocaleUrl::home($urlLocale);
        $canonicalUrl = self::siteUrl() . ($canonicalPath === '/' ? '' : $canonicalPath);
        $defaultPath = LocaleUrl::home(LanguageRegistry::defaultCode());

        return [
            'canonicalUrl' => $canonicalUrl,
            'ogImage' => self::ogImageUrl(),
            'ogType' => 'website',
            'robots' => 'index, follow',
            'twitterCard' => 'summary_large_image',
            'seoDefaultHref' => self::siteUrl() . ($defaultPath === '/' ? '' : $defaultPath),
            'hreflang' => self::hreflangForHome(),
        ];
    }

    /**
     * @return list<array{locale: string, href: string}>
     */
    public static function hreflangForHome(): array
    {
        $links = [];
        foreach (LanguageRegistry::list() as $code => $row) {
            if (! ($row['is_active'] ?? true)) {
                continue;
            }
            $path = LocaleUrl::home($code);
            $links[] = [
                'locale' => self::hreflangCode($code),
                'href' => self::siteUrl() . ($path === '/' ? '' : $path),
            ];
        }

        return $links;
    }

    private static function hreflangCode(string $locale): string
    {
        return match (strtolower($locale)) {
            'zh-cn' => 'zh-Hans',
            'zh-tw' => 'zh-Hant',
            default => str_replace('_', '-', strtolower($locale)),
        };
    }
}
