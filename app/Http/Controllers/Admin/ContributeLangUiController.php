<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Admin\Concerns\BuildsLangUiEditor;
use App\Http\Controllers\Controller;
use App\Services\LangUiFileService;
use App\Support\ContributeUrl;
use App\Support\LangUiAdminScope;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContributeLangUiController extends Controller
{
    use BuildsLangUiEditor;

    private const SCOPE = LangUiAdminScope::CONTRIBUTE;

    public function __construct(
        private readonly LangUiFileService $files,
    ) {}

    public function redirectToDefault(): RedirectResponse
    {
        return redirect()->route('admin.lang-ui.contribute.edit', ['locale' => 'vi']);
    }

    public function edit(string $locale): View|RedirectResponse
    {
        $locale = strtolower($locale);

        if (! $this->files->isAllowedLocale($locale)) {
            return redirect()->route('admin.lang-ui.contribute.edit', ['locale' => 'vi']);
        }

        $payload = $this->langUiEditorPayload(self::SCOPE, $locale, $this->files);

        return view('admin.lang-ui.contribute', array_merge(
            $payload,
            $this->langUiAiViewData(self::SCOPE, $locale),
            [
                'locale' => $locale,
                'saveUrl' => route('admin.lang-ui.contribute.save', ['locale' => $locale]),
                'localeEditRoute' => 'admin.lang-ui.contribute.edit',
                'publicPreviewUrl' => ContributeUrl::path($locale),
            ],
        ));
    }

    public function save(Request $request, string $locale): JsonResponse
    {
        return $this->saveLangUiBundle($request, $locale, self::SCOPE, $this->files);
    }
}
