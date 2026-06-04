<?php

namespace App\Support;

class LanguageRegistry
{
    /**
     * @return array<string, array<string, mixed>>
     */
    public static function list(): array
    {
        $list = config('language.list', []);
        if ($list !== []) {
            return $list;
        }

        return self::buildFromWallsora();
    }

    public static function defaultCode(): string
    {
        return (string) config('language.default_code', 'en');
    }

    /**
     * Danh sách đã chuẩn hóa cho nav / modal (có flag URL, code_short, …)
     *
     * @return array<string, array<string, mixed>>
     */
    public static function forNav(): array
    {
        $flags = config('language_flags', []);
        $out = [];
        $sort = 0;

        foreach (self::list() as $code => $row) {
            if (!($row['is_active'] ?? true)) {
                continue;
            }
            $cc = $row['flag_cc'] ?? ($flags[$code] ?? 'un');
            $out[$code] = [
                'code' => $code,
                'code_display' => strtoupper($code),
                'code_short' => self::codeShort($code),
                'name_native' => $row['name_native'] ?? $code,
                'name_english' => $row['name_english'] ?? strtoupper($code),
                'name_vi' => $row['name_vi'] ?? '',
                'dir' => $row['dir'] ?? 'ltr',
                'sort' => (int) ($row['sort'] ?? (++$sort)),
                'flag' => 'https://flagcdn.com/w20/' . $cc . '.png',
                'search' => self::searchBlob($code, $row),
            ];
        }

        uasort($out, static fn ($a, $b) => ($a['sort'] <=> $b['sort']) ?: strcmp($a['name_native'], $b['name_native']));

        return $out;
    }

    public static function codeShort(string $code): string
    {
        $code = strtolower($code);
        return match ($code) {
            'zh-cn' => 'CN',
            'zh-tw' => 'TW',
            'fil' => 'PH',
            default => strtoupper(strlen($code) <= 3 ? $code : substr(str_replace('-', '', $code), 0, 2)),
        };
    }

    public static function htmlLang(string $code): string
    {
        return match (strtolower($code)) {
            'zh-cn' => 'zh-CN',
            'zh-tw' => 'zh-TW',
            'fil' => 'fil',
            default => strtolower($code),
        };
    }

    public static function textDir(string $code): string
    {
        $code = strtolower(trim($code));
        $dir = self::list()[$code]['dir'] ?? 'ltr';

        return $dir === 'rtl' ? 'rtl' : 'ltr';
    }

    /**
     * @return array<string, string>
     */
    public static function navUi(?string $locale = null): array
    {
        $locale = $locale ?: self::defaultCode();
        $ui = config('language.nav_ui', []);
        if (isset($ui[$locale])) {
            return $ui[$locale];
        }

        return $ui['en'] ?? $ui['vi'] ?? [];
    }

    /**
     * @param array<string, mixed> $row
     */
    private static function searchBlob(string $code, array $row): string
    {
        $parts = [
            $code,
            $row['name_native'] ?? '',
            $row['name_english'] ?? '',
            $row['name_vi'] ?? '',
            strtoupper($code),
            self::codeShort($code),
        ];

        return mb_strtolower(implode(' ', $parts), 'UTF-8');
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private static function buildFromWallsora(): array
    {
        $path = base_path('../wallsora.dev/config/language.php');
        if (!is_file($path)) {
            return [];
        }

        /** @var array<string, array<string, mixed>> $ws */
        $ws = require $path;
        $flags = config('language_flags', []);
        $list = [];
        $order = 0;

        foreach ($ws as $code => $row) {
            $order++;
            $list[$code] = [
                'code' => $code,
                'name_native' => (string) ($row['name_by_language'] ?? $code),
                'name_english' => self::englishLabel($row),
                'name_vi' => (string) ($row['name'] ?? ''),
                'flag_cc' => $flags[$code] ?? $code,
                'dir' => (string) ($row['dir'] ?? 'ltr'),
                'sort' => $code === 'vi' ? 0 : $order,
                'is_active' => true,
                'is_default' => $code === 'vi',
            ];
        }

        // Bổ sung locale Hitour (không có trong wallsora gốc)
        foreach (self::hitourExtras() as $code => $extra) {
            if (!isset($list[$code])) {
                $list[$code] = array_merge([
                    'code' => $code,
                    'flag_cc' => $flags[$code] ?? $code,
                    'dir' => 'ltr',
                    'is_active' => true,
                    'sort' => 999,
                ], $extra);
            }
        }

        unset($list['zh']);

        return $list;
    }

    /**
     * @param array<string, mixed> $row
     */
    private static function englishLabel(array $row): string
    {
        $native = (string) ($row['name_by_language'] ?? '');
        if (preg_match('/\(([^)]+)\)\s*$/u', $native, $m)) {
            return trim($m[1]);
        }

        return $native;
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private static function hitourExtras(): array
    {
        return [
            'zh-cn' => [
                'name_native' => '简体中文',
                'name_english' => 'Chinese (Simplified)',
                'name_vi' => 'Tiếng Trung (Giản thể)',
                'sort' => 3,
            ],
            'zh-tw' => [
                'name_native' => '繁體中文',
                'name_english' => 'Chinese (Traditional)',
                'name_vi' => 'Tiếng Trung (Phồn thể)',
                'sort' => 4,
            ],
        ];
    }
}
