<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsLangUiEditor;
use App\Http\Controllers\Controller;
use App\Services\LangUiFileService;
use App\Support\LangUiAdminScope;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LandingLangUiController extends Controller
{
    use BuildsLangUiEditor;

    private const SCOPE = LangUiAdminScope::LANDING;

    public function __construct(
        private readonly LangUiFileService $files,
    ) {}

    public function redirectToDefault(): RedirectResponse
    {
        return redirect()->route('admin.lang-ui.edit', ['locale' => 'vi']);
    }

    public function edit(string $locale): View|RedirectResponse
    {
        $locale = strtolower($locale);

        if (! $this->files->isAllowedLocale($locale)) {
            return redirect()->route('admin.lang-ui.edit', ['locale' => 'vi']);
        }

        $payload = $this->langUiEditorPayload(self::SCOPE, $locale, $this->files);

        return view('admin.lang-ui.landing', array_merge(
            $payload,
            $this->langUiAiViewData(self::SCOPE, $locale),
            [
                'locale' => $locale,
                'saveUrl' => route('admin.lang-ui.save', ['locale' => $locale]),
                'localeEditRoute' => 'admin.lang-ui.edit',
                'publicPreviewUrl' => $locale === $payload['masterLocale']
                    ? route('home')
                    : route('home.locale', ['locale' => $locale]),
            ],
        ));
    }

    public function save(Request $request, string $locale): JsonResponse
    {
        return $this->saveLangUiBundle($request, $locale, self::SCOPE, $this->files);
    }
}
