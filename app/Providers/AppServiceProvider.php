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
     * Nạp lang_ui lúc runtime — không đưa vào config:cache.
     */
    private function bootLangUiBundles(): void
    {
        // Luôn nạp runtime — không dùng config:cache cho lang_ui (50 locale × nghìn key).
        config(['lang_ui' => LangUi::all()]);
    }
}
