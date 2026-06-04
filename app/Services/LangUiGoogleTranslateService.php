<?php

declare(strict_types=1);

namespace App\Services;

final class LangUiGoogleTranslateService
{
    /** @var array<string, string> */
    private static array $tlMap = [
        'zh-cn' => 'zh-CN',
        'zh-tw' => 'zh-TW',
        'he' => 'iw',
        'fil' => 'tl',
        'jv' => 'jw',
    ];

    private bool $utilsLoaded = false;

    public function translate(string $text, string $locale): string
    {
        $this->loadUtils();
        if (trim($text) === '') {
            return $text;
        }

        $locale = strtolower($locale);
        $cache = [];

        return translateValue($text, $locale, $cache, $this->googleTl(...));
    }

    public function googleTl(string $locale): string
    {
        $locale = strtolower($locale);

        return self::$tlMap[$locale] ?? $locale;
    }

    private function loadUtils(): void
    {
        if ($this->utilsLoaded) {
            return;
        }
        require_once base_path('scripts/translation-text-utils.php');
        $this->utilsLoaded = true;
    }
}
