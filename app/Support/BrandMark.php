<?php

declare(strict_types=1);

namespace App\Support;

final class BrandMark
{
    /**
     * Logo nav / loader — ninedashline.dev.
     *
     * @return array{aria: string, logo_alt: string, logo_url: string, name: string}
     */
    public static function data(?string $urlLocale = null): array
    {
        $base = rtrim((string) config('landing.images_base'), '/');
        $logoFile = (string) config('landing.logo_file', 'logo-ninedashline.png');
        $name = t('nav_logo_name');

        return [
            'aria' => $name . ' — ninedashline.dev',
            'logo_alt' => $name,
            'logo_url' => $base . '/' . ltrim($logoFile, '/'),
            'name' => $name,
        ];
    }

    public static function faviconUrl(): string
    {
        $base = rtrim((string) config('landing.images_base'), '/');
        $file = (string) config('landing.favicon_file', 'favicon-ninedashline.png');

        return $base . '/' . ltrim($file, '/');
    }
}
