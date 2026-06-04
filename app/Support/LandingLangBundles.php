<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Bundle lang_ui landing — ninedashline.dev (đường chín đoạn).
 */
final class LandingLangBundles
{
    /** @return list<string> */
    public static function files(): array
    {
        return [
            'nav.php',
            'meta.php',
            'hero.php',
            'common.php',
            'what.php',
            'origin.php',
            'dispute.php',
            'opposition.php',
            'witnesses.php',
            'verdict.php',
            'sovereignty.php',
            'footer.php',
        ];
    }

    public static function stem(string $filename): string
    {
        return str_replace('.php', '', $filename);
    }

    /** @return list<string> */
    public static function stems(): array
    {
        return array_map(static fn (string $f): string => self::stem($f), self::files());
    }
}
