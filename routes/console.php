<?php

use App\Support\LangUi;
use App\Support\LandingLangBundles;
use App\Support\LocaleUrl;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

Artisan::command('lang-ui:status', function () {
    $vi = LangUi::forLocale('vi');
    $viCount = count($vi);
    $loaded = config('lang_ui');
    $loadedLocales = is_array($loaded) ? array_keys($loaded) : [];
    $expected = LangUi::contentLocales();

    $this->info('lang_ui master (vi) keys: ' . $viCount);
    $this->info('contentLocales (đủ file): ' . count($expected));
    if (count($expected) <= 8) {
        $this->line('  → ' . implode(', ', $expected));
    }
    $this->info('config(lang_ui) sau boot: ' . count($loadedLocales) . ' locale');
    $this->line('required stems: ' . implode(', ', LandingLangBundles::requiredStems()));
    $this->line('optional master-only: ' . implode(', ', LandingLangBundles::optionalMasterOnlyStems()));

    if (is_file(base_path('bootstrap/cache/config.php'))) {
        $this->warn('bootstrap/cache/config.php tồn tại — chạy: php artisan optimize:clear');
    }

    $samples = ['en', 'ja', 'fr', 'vi'];
    $this->newLine();
    $this->table(
        ['URL locale', 'contentLocale', 'hasLocale', 'keys in config', 'hero_title_line1 (preview)'],
        array_map(function (string $code) use ($loaded) {
            $content = LocaleUrl::contentLocale($code);
            $keys = is_array($loaded[$content] ?? null) ? count($loaded[$content]) : 0;
            $title = is_array($loaded[$content] ?? null)
                ? (string) ($loaded[$content]['hero_title_line1'] ?? '—')
                : '—';

            return [
                $code,
                $content,
                LangUi::hasLocale($code) ? 'yes' : 'no',
                (string) $keys,
                mb_substr($title, 0, 48),
            ];
        }, $samples)
    );

    if (count($loadedLocales) < 2) {
        $this->error('Chỉ có ≤1 locale trong config — mọi ngôn ngữ sẽ fallback tiếng Việt.');
    }

    return 0;
})->purpose('Kiểm tra nạp lang_ui đa ngôn ngữ');
