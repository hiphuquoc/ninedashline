<?php

namespace App\Support;

/**
 * Gộp các file config/lang_ui/{locale}/*.php thành một mảng key → text (giống Hitour).
 */
final class LangUi
{
    /** Locale master — luôn có trong bundle. */
    public const MASTER_LOCALE = 'vi';

    /**
     * File nội dung mới — đã có ở master (vi) nhưng CHƯA dịch sang locale khác.
     * Số key của các file này được trừ khỏi baseline khi xét locale "đủ key"
     * (tránh demote toàn bộ locale về default chỉ vì phần mới chưa dịch).
     * Khi đã publish đủ bản dịch cho các file này, xóa hết mục trong mảng này.
     *
     * @var list<string>
     */
    private const PENDING_TRANSLATION_FILES = [];

    /**
     * @return list<string> Locale có đủ key như vi (tự quét thư mục lang_ui).
     */
    public static function contentLocales(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }

        $master = self::forLocale(self::MASTER_LOCALE);
        $masterCount = count($master);
        $minKeys = self::minKeysForLocale($masterCount);
        $locales = [self::MASTER_LOCALE];

        foreach (glob(config_path('lang_ui/*'), GLOB_ONLYDIR) ?: [] as $dir) {
            $code = basename($dir);
            if ($code === self::MASTER_LOCALE || $code === '') {
                continue;
            }
            if ($masterCount > 0 && self::localeDirLooksComplete($dir)) {
                $locales[] = $code;
            }
        }

        sort($locales);

        return $cache = array_values(array_unique($locales));
    }

    /**
     * @return array<string, array<string, string>>
     */
    public static function all(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }

        $out = [];
        foreach (self::contentLocales() as $locale) {
            $out[$locale] = self::forLocale($locale);
        }

        return $cache = $out;
    }

    /**
     * @return array<string, string>
     */
    public static function forLocale(string $locale): array
    {
        $locale = strtolower($locale);
        $dir = config_path('lang_ui/' . $locale);
        if (!is_dir($dir)) {
            return [];
        }

        $merged = [];
        foreach (glob($dir . '/*.php') ?: [] as $file) {
            /** @var array<string, string> $chunk */
            $chunk = require $file;
            $merged = array_merge($merged, $chunk);
        }

        $contribConfig = $dir . '/chung_suc.php';
        if (is_file($contribConfig)) {
            /** @var array<string, string> $contrib */
            $contrib = require $contribConfig;
            $merged = array_merge($merged, $contrib);
        } else {
            $letterData = base_path('scripts/lang-data/contribute/' . $locale . '.php');
            $pathsData = base_path('scripts/lang-data/contribute-paths/' . $locale . '.php');
            if (is_file($letterData)) {
                /** @var array<string, string> $letter */
                $letter = require $letterData;
                $merged = array_merge($merged, $letter);
            }
            if (is_file($pathsData)) {
                /** @var array<string, string> $paths */
                $paths = require $pathsData;
                $merged = array_merge($merged, $paths);
            }
        }

        return $merged;
    }

    public static function hasLocale(string $locale): bool
    {
        $locale = strtolower($locale);

        if (!in_array($locale, self::contentLocales(), true)) {
            return false;
        }

        $master = self::forLocale(self::MASTER_LOCALE);
        if ($master === []) {
            return false;
        }

        $translated = self::forLocale($locale);

        // Trừ phần nội dung mới chưa dịch khỏi baseline để không demote cả locale.
        $baseline = max(0, count($master) - self::pendingTranslationKeyCount());

        return count($translated) >= self::minKeysForLocale($baseline);
    }

    /** Tổng số key trong các file master chưa dịch (PENDING_TRANSLATION_FILES). */
    private static function pendingTranslationKeyCount(): int
    {
        $dir = config_path('lang_ui/' . self::MASTER_LOCALE);
        $count = 0;
        foreach (self::PENDING_TRANSLATION_FILES as $name) {
            $file = $dir . '/' . $name . '.php';
            if (is_file($file)) {
                /** @var array<string, string> $chunk */
                $chunk = require $file;
                $count += is_array($chunk) ? count($chunk) : 0;
            }
        }

        return $count;
    }

    /** Đủ file bundle landing (cùng số file master). */
    private static function localeDirLooksComplete(string $dir): bool
    {
        $masterFiles = LandingLangBundles::files();
        $masterCount = count($masterFiles);
        if ($masterCount === 0) {
            return false;
        }

        foreach ($masterFiles as $file) {
            if (! is_file($dir . '/' . $file)) {
                return false;
            }
        }

        return true;
    }

    /** Cho phép lệch vài key (key tuỳ chọn) — tránh fallback cả locale về en. */
    private static function minKeysForLocale(int $masterCount): int
    {
        if ($masterCount <= 0) {
            return 0;
        }

        return max(1, $masterCount - 3);
    }
}
