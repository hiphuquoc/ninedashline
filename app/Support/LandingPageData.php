<?php

declare(strict_types=1);

namespace App\Support;

class LandingPageData
{
    /**
     * @return array<string, mixed>
     */
    public static function view(): array
    {
        $landing = config('landing');
        $urlLocale = url_locale();
        $locale = current_locale();
        $navLanguages = LanguageRegistry::forNav();
        $navLangUi = self::navUiFlat($urlLocale, count($navLanguages));

        return [
            'locale' => $locale,
            'urlLocale' => $urlLocale,
            'htmlLang' => html_lang_attr($locale),
            'googleFontsUrl' => $landing['fonts']['google_fonts_url'],
            'imgPlaceholder' => $landing['img_placeholder'],
            'metaTitle' => t('meta_title'),
            'metaDescription' => t('meta_description'),
            'metaOgTitle' => t('meta_og_title'),
            'metaOgDescription' => t('meta_og_description'),
            'metaRobots' => 'index, follow',
            'navDefaultLocale' => $urlLocale,
            'navLanguages' => $navLanguages,
            'navLangUi' => $navLangUi,
            'navLangCount' => count($navLanguages),
            'navRailSteps' => self::navRailStepsResolved(),
            'ancientChineseMaps' => AncientChineseMaps::resolved(),
            'navRootUrl' => LocaleUrl::home($urlLocale),
            'ecosystemParacelUrl' => EcosystemSites::paracel($urlLocale),
            'landingScriptConfig' => self::landingScriptConfig($urlLocale, $navLanguages, $navLangUi, $landing),
            ...SeoMeta::landing($urlLocale),
        ];
    }

    /**
     * @param  array<string, array<string, mixed>>  $navLanguages
     * @param  array<string, string>  $navLangUi
     * @return array<string, mixed>
     */
    private static function landingScriptConfig(string $urlLocale, array $navLanguages, array $navLangUi, array $landing): array
    {
        return [
            'imgPlaceholder' => $landing['img_placeholder'],
            'ambientAudioUrl' => $landing['ambient_audio'] ?? '',
            'locale' => current_locale(),
            'localeDefault' => LocaleUrl::defaultCode(),
            'localeHomePaths' => LocaleUrl::homePathsMap(),
            'contentLocales' => LangUi::contentLocales(),
            'navLangUi' => $navLangUi,
            'navLanguages' => $navLanguages,
            'navDefaultLocale' => $urlLocale,
            'shareTitle' => t('meta_title'),
            'shareText' => t('meta_description'),
            'shareTwitterText' => t('meta_description'),
            'toastShared' => t('toast_shared'),
            'toastShareOpen' => t('toast_share_open'),
            'toastCopied' => t('toast_copied'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function navUiFlat(string $locale, int $langCount): array
    {
        $keys = [
            'dialog_title', 'language', 'search_placeholder', 'cancel', 'apply',
            'sound_off', 'sound_on', 'contribute', 'lang_trigger', 'close', 'no_results', 'display_hint',
        ];
        $out = [];
        foreach ($keys as $key) {
            $out[$key] = $key === 'display_hint'
                ? t('display_hint', ['count' => $langCount])
                : t($key);
        }

        foreach (self::navRailSteps() as $step) {
            $out[$step['label_key']] = t($step['label_key']);
        }

        return $out;
    }

    /**
     * @return list<array{section: string, label_key: string}>
     */
    public static function navRailSteps(): array
    {
        return [
            ['section' => 'what', 'label_key' => 'nav_rail_what'],
            ['section' => 'origin', 'label_key' => 'nav_rail_origin'],
            ['section' => 'dispute', 'label_key' => 'nav_rail_dispute'],
            ['section' => 'opposition', 'label_key' => 'nav_rail_opposition'],
            ['section' => 'witnesses', 'label_key' => 'nav_rail_witnesses'],
            ['section' => 'verdict', 'label_key' => 'nav_rail_verdict'],
            ['section' => 'sovereignty', 'label_key' => 'nav_rail_sovereignty'],
        ];
    }

    /**
     * @return list<array{section: string, label_key: string, label: string}>
     */
    public static function navRailStepsResolved(): array
    {
        return array_map(static function (array $step): array {
            return [
                'section' => $step['section'],
                'label_key' => $step['label_key'],
                'label' => t($step['label_key']),
            ];
        }, self::navRailSteps());
    }
}
