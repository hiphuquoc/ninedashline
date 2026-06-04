<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Section admin trang Chung sức — một bundle chung_suc.php, lọc key theo nhóm.
 */
final class ContributeLangSections
{
    /**
     * @return list<array{
     *   id: string,
     *   label: string,
     *   anchor: string,
     *   bundles: list<string>,
     *   note: ?string,
     *   shared_landing: bool,
     *   key_filter: callable(string): bool
     * }>
     */
    public static function forAdmin(): array
    {
        $bundle = ['chung_suc'];

        $sections = [
            [
                'id' => 'page',
                'label' => 'Trang & SEO',
                'anchor' => '',
                'bundles' => $bundle,
                'note' => null,
                'shared_landing' => false,
                'key_filter' => static fn (string $k): bool => str_starts_with($k, 'cs_page_')
                    || str_starts_with($k, 'cs_meta_'),
            ],
            [
                'id' => 'nav',
                'label' => 'Menu Chung sức',
                'anchor' => '#cs-letter',
                'bundles' => $bundle,
                'note' => null,
                'shared_landing' => false,
                'key_filter' => static fn (string $k): bool => str_starts_with($k, 'cs_nav_'),
            ],
            [
                'id' => 'letter',
                'label' => 'Tâm thư',
                'anchor' => '#cs-letter',
                'bundles' => $bundle,
                'note' => null,
                'shared_landing' => false,
                'key_filter' => static fn (string $k): bool => str_starts_with($k, 'cs_letter_'),
            ],
            [
                'id' => 'paths',
                'label' => '4 bước đồng hành',
                'anchor' => '#cs-contribute',
                'bundles' => $bundle,
                'note' => 'Nội dung này dùng chung partial participation-ways — hiển thị trên landing (#action) và trang Chung sức. Chỉnh tại đây, không chỉnh trên trang landing.',
                'shared_landing' => true,
                'key_filter' => static fn (string $k): bool => str_starts_with($k, 'cs_paths_')
                    || str_starts_with($k, 'cs_path_'),
            ],
            [
                'id' => 'payments',
                'label' => 'Thanh toán & PayPal',
                'anchor' => '#cs-contribute',
                'bundles' => $bundle,
                'note' => null,
                'shared_landing' => false,
                'key_filter' => static fn (string $k): bool => str_starts_with($k, 'cs_pay_'),
            ],
            [
                'id' => 'donor',
                'label' => 'Ghi nhận nhà tài trợ',
                'anchor' => '#cs-contribute',
                'bundles' => $bundle,
                'note' => null,
                'shared_landing' => false,
                'key_filter' => static fn (string $k): bool => str_starts_with($k, 'cs_donor_'),
            ],
        ];

        return $sections;
    }

    public static function bundleLabel(string $stem): string
    {
        return $stem === 'chung_suc' ? 'Chung sức' : ucfirst(str_replace('_', ' ', $stem));
    }

    public static function headerTheme(string $sectionId): string
    {
        return match ($sectionId) {
            'page' => 'document',
            'nav' => 'page',
            'letter' => 'profile',
            'paths' => 'trainer',
            'payments' => 'warning',
            'donor' => 'success',
            default => 'page',
        };
    }

    public static function sectionLabel(string $sectionId): string
    {
        foreach (self::forAdmin() as $section) {
            if ($section['id'] === $sectionId) {
                return $section['label'];
            }
        }

        return $sectionId;
    }
}
