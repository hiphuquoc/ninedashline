<?php

namespace App\Support;

/**
 * Gộp các file config/lang_ui/{locale}/*.php thành một mảng key → text (giống Hitour / hoangsa.dev).
 */
final class LangUi
{
    /** Locale master — luôn có trong bundle. */
    public const MASTER_LOCALE = 'vi';

    /**
     * File nội dung mới — đã có ở master (vi) nhưng CHƯA dịch sang locale khác.
     * Số key của các file này được trừ khỏi baseline khi xét locale "đủ key".
     *
     * @var list<string>
     */
    private const PENDING_TRANSLATION_FILES = [];

    /**
     * @return list<string> Locale có đủ file bundle (tự quét thư mục lang_ui).
     */
    public static function contentLocales(): array
    {
        static $cache = null;
        if ($cache !== null) {
            return $cache;
        }

        $master = self::forLocale(self::MASTER_LOCALE);
        $masterCount = count($master);
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
        if (! is_dir($dir)) {
            return [];
        }

        $merged = [];
        foreach (glob($dir . '/*.php') ?: [] as $file) {
            /** @var array<string, string> $chunk */
            $chunk = require $file;
            $merged = array_merge($merged, $chunk);
        }

        return $merged;
    }

    public static function hasLocale(string $locale): bool
    {
        $locale = strtolower($locale);

        if (! in_array($locale, self::contentLocales(), true)) {
            return false;
        }

        $master = self::forLocale(self::MASTER_LOCALE);
        if ($master === []) {
            return false;
        }

        $translated = self::forLocale($locale);
        $baseline = max(0, count($master) - self::pendingTranslationKeyCount() - self::optionalMasterOnlyKeyCount());

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

    /** Key chỉ có trong file optional master (vd. ancient_maps chỉ vi). */
    private static function optionalMasterOnlyKeyCount(): int
    {
        $dir = config_path('lang_ui/' . self::MASTER_LOCALE);
        $count = 0;
        foreach (LandingLangBundles::optionalMasterOnlyStems() as $stem) {
            $file = $dir . '/' . $stem . '.php';
            if (is_file($file)) {
                /** @var array<string, string> $chunk */
                $chunk = require $file;
                $count += is_array($chunk) ? count($chunk) : 0;
            }
        }

        return $count;
    }

    /** Đủ file bundle landing (theo LandingLangBundles, trừ optional master-only). */
    private static function localeDirLooksComplete(string $dir): bool
    {
        $have = self::phpStemsInDir($dir);
        if ($have === []) {
            return false;
        }

        foreach (LandingLangBundles::requiredStems() as $stem) {
            if (! in_array($stem, $have, true)) {
                return false;
            }
        }

        return true;
    }

    /**
     * @return list<string>
     */
    private static function phpStemsInDir(string $dir): array
    {
        $stems = [];
        foreach (glob(rtrim($dir, '/') . '/*.php') ?: [] as $file) {
            $stems[] = basename($file, '.php');
        }

        sort($stems);

        return $stems;
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
