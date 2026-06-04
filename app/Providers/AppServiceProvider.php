<?php

namespace App\Providers;

use App\Support\LangUi;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        $this->bootLangUiBundles();
    }

    /**
     * Nạp lang_ui lúc runtime — không đưa vào config:cache (50 locale × nghìn key).
     */
    private function bootLangUiBundles(): void
    {
        $bundles = LangUi::all();
        $existing = config('lang_ui');

        if ($this->cachedLangUiLooksComplete($existing, $bundles)) {
            return;
        }

        config(['lang_ui' => $bundles]);
    }

    /**
     * @param  mixed  $existing
     * @param  array<string, array<string, string>>  $bundles
     */
    private function cachedLangUiLooksComplete(mixed $existing, array $bundles): bool
    {
        if (! is_array($existing) || $bundles === []) {
            return false;
        }

        foreach (array_keys($bundles) as $locale) {
            if (! isset($existing[$locale]) || ! is_array($existing[$locale])) {
                return false;
            }
            if (count($existing[$locale]) < max(20, (int) floor(count($bundles[$locale]) * 0.9))) {
                return false;
            }
        }

        return true;
    }
}
