<?php

declare(strict_types=1);

namespace App\Support;

final class EcosystemSites
{
    /** @var list<string> */
    public const SITE_KEYS = ['paracel', 'spratly', 'hoangsa', 'truongsa', 'ninedashline'];

    public static function url(string $site, string $locale): string
    {
        $sites = config('ecosystem.sites', []);
        if (! is_array($sites)) {
            return '';
        }

        $base = rtrim((string) ($sites[$site] ?? ''), '/');
        if ($base === '') {
            return '';
        }

        $locale = strtolower(trim($locale));
        if ($locale === '') {
            return $base;
        }

        return $base . '/' . rawurlencode($locale);
    }

    public static function paracel(string $locale): string
    {
        return self::url('paracel', $locale);
    }

    public static function spratly(string $locale): string
    {
        return self::url('spratly', $locale);
    }

    public static function hoangsa(string $locale): string
    {
        return self::url('hoangsa', $locale);
    }

    public static function truongsa(string $locale): string
    {
        return self::url('truongsa', $locale);
    }

    public static function ninedashline(string $locale): string
    {
        return self::url('ninedashline', $locale);
    }

    /**
     * Khối hướng dẫn href cho prompt dịch AI (chỉ áp dụng thuộc tính href).
     */
    public static function promptHrefRulesForLocale(string $locale): string
    {
        $locale = strtolower(trim($locale));
        $lines = [
            'Quy tắc liên kết hệ sinh thái — CHỈ sửa trong thuộc tính href của thẻ <a> (không đổi text hiển thị, không đổi nhãn nút, không đổi domain/TTL trong chữ):',
            '- Mã locale đích đang dịch: ' . $locale . ' — mọi URL site hệ sinh thái trong href phải có segment /' . $locale . ' ngay sau domain.',
            '- Công thức: https://{domain}/' . $locale . ' (không bỏ /' . $locale . ', không giữ /vi khi đích là ' . $locale . ').',
        ];

        foreach (self::SITE_KEYS as $key) {
            $example = self::url($key, $locale);
            if ($example !== '') {
                $lines[] = '- ' . $key . ' → ' . $example;
            }
        }

        $lines[] = '- Placeholder :paracel, :spratly, :hoangsa, :truongsa, :ninedashline: GIỮ NGUYÊN trong bản dịch (runtime PHP thay URL); không thay bằng URL cứng.';
        $lines[] = '- Nếu bản gốc vi đã có URL đầy đủ trong href (vd. https://paracelislands.net/vi): đổi segment locale thành /' . $locale . ', giữ https và domain.';
        $lines[] = '- Không thêm/xóa thẻ <a>; chỉ dịch text giữa thẻ và chỉnh href như trên.';

        return implode("\n", $lines);
    }
}
