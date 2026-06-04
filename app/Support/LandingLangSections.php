<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Section landing ninedashline.dev → bundle lang_ui (nhãn admin).
 */
final class LandingLangSections
{
    /**
     * @return list<array{
     *   id: string,
     *   label: string,
     *   anchor: string,
     *   bundles: list<string>,
     *   note?: string|null
     * }>
     */
    public static function forAdmin(): array
    {
        $map = [
            'nav' => ['label' => 'Menu & điều hướng', 'anchor' => '#hero', 'bundles' => ['nav']],
            'meta' => ['label' => 'SEO & Meta', 'anchor' => '', 'bundles' => ['meta']],
            'hero' => ['label' => 'Hero (mở đầu)', 'anchor' => '#hero', 'bundles' => ['hero']],
            'common' => ['label' => 'Loader & UI chung', 'anchor' => '', 'bundles' => ['common']],
            'what' => ['label' => '01 · Định nghĩa', 'anchor' => '#what', 'bundles' => ['what']],
            'origin' => ['label' => '02 · Nguồn gốc', 'anchor' => '#origin', 'bundles' => ['origin']],
            'dispute' => ['label' => '03 · Tranh cãi', 'anchor' => '#dispute', 'bundles' => ['dispute']],
            'opposition' => ['label' => '04 · Phản đối', 'anchor' => '#opposition', 'bundles' => ['opposition']],
            'witnesses' => ['label' => '05 · Bản đồ cổ', 'anchor' => '#witnesses', 'bundles' => ['witnesses']],
            'verdict' => ['label' => '06 · PCA 2016', 'anchor' => '#verdict', 'bundles' => ['verdict']],
            'sovereignty' => ['label' => '07 · Hoàng Sa – Trường Sa', 'anchor' => '#sovereignty', 'bundles' => ['sovereignty']],
            'footer' => ['label' => 'Footer', 'anchor' => '#footer', 'bundles' => ['footer']],
        ];

        $out = [];
        foreach ($map as $id => $row) {
            $out[] = [
                'id' => $id,
                'label' => $row['label'],
                'anchor' => $row['anchor'],
                'bundles' => $row['bundles'],
                'note' => $row['note'] ?? null,
            ];
        }

        return $out;
    }

    public static function bundleLabel(string $stem): string
    {
        foreach (self::forAdmin() as $section) {
            if (in_array($stem, $section['bundles'], true)) {
                return $section['label'];
            }
        }

        return ucfirst(str_replace('_', ' ', $stem));
    }

    public static function headerTheme(string $sectionId): string
    {
        return match ($sectionId) {
            'nav', 'common' => 'page',
            'meta', 'dispute', 'verdict', 'opposition' => 'document',
            'hero', 'sovereignty' => 'profile',
            'what', 'origin', 'witnesses' => 'blog',
            'footer' => 'blog',
            default => 'page',
        };
    }
}
