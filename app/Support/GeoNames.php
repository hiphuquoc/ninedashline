<?php

namespace App\Support;

/**
 * Tên địa danh theo locale — docs/translation/geo-names.json (dùng chung hoangsa.dev · ninedashline.dev).
 */
final class GeoNames
{
    /** @var list<string> */
    public const FIELD_KEYS = [
        'vietnam',
        'hoang_sa',
        'truong_sa',
        'combined',
        'nine_dash_line',
        'china',
    ];

    /** @var array<string, array<string, string>>|null */
    private static ?array $map = null;

    /**
     * Nhãn hiển thị trong prompt admin / export AI.
     *
     * @return array<string, string>
     */
    public static function fieldLabels(): array
    {
        return [
            'vietnam' => 'Việt Nam / quốc gia (vietnam)',
            'hoang_sa' => 'Hoàng Sa (Paracel)',
            'truong_sa' => 'Trường Sa (Spratly)',
            'combined' => 'Cặp hai quần đảo (combined)',
            'nine_dash_line' => 'Đường lưỡi bò / Nine-Dash Line (tên địa phương)',
            'china' => 'Trung Quốc / China (tên địa phương)',
        ];
    }

    /**
     * @return array{
     *   vietnam: string,
     *   hoang_sa: string,
     *   truong_sa: string,
     *   combined: string,
     *   nine_dash_line: string,
     *   china: string
     * }
     */
    public static function forLocale(?string $locale = null): array
    {
        $locale = strtolower(trim($locale ?? current_locale()));
        $map = self::load();

        if (isset($map[$locale])) {
            return $map[$locale];
        }

        foreach ([url_locale(), 'en', 'vi'] as $fallback) {
            $fallback = strtolower(trim((string) $fallback));
            if ($fallback !== '' && $fallback !== $locale && isset($map[$fallback])) {
                return $map[$fallback];
            }
        }

        return self::defaultRow();
    }

    public static function vietnam(?string $locale = null): string
    {
        return self::forLocale($locale)['vietnam'];
    }

    public static function combined(?string $locale = null): string
    {
        return self::forLocale($locale)['combined'];
    }

    public static function nineDashLine(?string $locale = null): string
    {
        return self::forLocale($locale)['nine_dash_line'];
    }

    public static function china(?string $locale = null): string
    {
        return self::forLocale($locale)['china'];
    }

    /** Khối markdown cho prompt dịch admin (Copy / AI). */
    public static function promptBlockForLocale(?string $locale = null): string
    {
        $locale = strtolower(trim($locale ?? current_locale()));
        $path = base_path('docs/translation/geo-names.json');
        if (! is_file($path)) {
            return '- (Không tìm thấy geo-names.json — dùng tên chuẩn quốc tế cho locale ' . $locale . ')';
        }

        $raw = json_decode((string) file_get_contents($path), true);
        if (! is_array($raw)) {
            return '- (geo-names.json không hợp lệ)';
        }

        $row = $raw[$locale] ?? $raw['en'] ?? null;
        if (! is_array($row)) {
            return '- (Chưa có map tên địa danh cho locale ' . $locale . ')';
        }

        $lines = [];
        foreach (self::fieldLabels() as $key => $label) {
            if (! empty($row[$key])) {
                $lines[] = '- ' . $label . ': «' . $row[$key] . '»';
            }
        }

        return $lines !== [] ? implode("\n", $lines) : '- (Trống)';
    }

    /**
     * @return array{
     *   vietnam: string,
     *   hoang_sa: string,
     *   truong_sa: string,
     *   combined: string,
     *   nine_dash_line: string,
     *   china: string
     * }
     */
    private static function defaultRow(): array
    {
        return [
            'vietnam' => 'Việt Nam',
            'hoang_sa' => 'Hoàng Sa',
            'truong_sa' => 'Trường Sa',
            'combined' => 'Hoàng Sa - Trường Sa',
            'nine_dash_line' => 'Đường lưỡi bò',
            'china' => 'Trung Quốc',
        ];
    }

    /**
     * @return array<string, array<string, string>>
     */
    private static function load(): array
    {
        if (self::$map !== null) {
            return self::$map;
        }

        $path = base_path('docs/translation/geo-names.json');
        if (! is_file($path)) {
            return self::$map = [];
        }

        $raw = json_decode((string) file_get_contents($path), true);
        if (! is_array($raw)) {
            return self::$map = [];
        }

        $defaults = self::defaultRow();
        $out = [];
        foreach ($raw as $code => $row) {
            if (! is_string($code) || str_starts_with($code, '_') || ! is_array($row)) {
                continue;
            }
            $out[$code] = [];
            foreach (self::FIELD_KEYS as $key) {
                $out[$code][$key] = (string) ($row[$key] ?? $defaults[$key]);
            }
        }

        return self::$map = $out;
    }
}
