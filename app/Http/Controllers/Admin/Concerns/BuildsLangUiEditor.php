<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin\Concerns;

use App\Services\LangUiFileService;
use App\Support\ContributeLangSections;
use App\Support\LangUi;
use App\Support\LangUiAdminScope;
use App\Support\LangUiAiPrompts;
use App\Support\LandingLangBundles;
use App\Support\LandingLangSections;
use App\Support\LanguageRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

trait BuildsLangUiEditor
{
    /**
     * @return array{
     *   sections: list<array<string, mixed>>,
     *   languages: array<string, array<string, mixed>>,
     *   statusMap: array<string, bool>,
     *   currentLang: ?array<string, mixed>,
     *   isMaster: bool,
     *   masterLocale: string
     * }
     */
    protected function langUiEditorPayload(string $scope, string $locale, LangUiFileService $files): array
    {
        $masterLocale = $files->masterLocale();
        $languages = $this->languagesForSwitcher($files);
        $statusMap = [];
        foreach ($languages as $code => $_lang) {
            $statusMap[$code] = $files->localeLooksTranslated($code);
        }

        $sectionDefs = $scope === LangUiAdminScope::CONTRIBUTE
            ? ContributeLangSections::forAdmin()
            : LandingLangSections::forAdmin();

        $sections = [];
        foreach ($sectionDefs as $section) {
            $bundles = [];
            foreach ($section['bundles'] as $stem) {
                $master = $files->readBundle($masterLocale, $stem, $scope);
                $filter = $section['key_filter'] ?? null;
                $fields = [];
                foreach ($master as $key => $viText) {
                    if (is_callable($filter) && ! $filter($key)) {
                        continue;
                    }
                    $target = $files->readBundle($locale, $stem, $scope);
                    $fields[] = [
                        'key' => $key,
                        'vi' => $viText,
                        'value' => $target[$key] ?? '',
                        'is_html' => $this->langUiFieldIsHtml($key, $viText),
                    ];
                }
                if ($fields === []) {
                    continue;
                }
                $bundles[] = [
                    'stem' => $stem,
                    'label' => $scope === LangUiAdminScope::CONTRIBUTE
                        ? ContributeLangSections::bundleLabel($stem)
                        : LandingLangSections::bundleLabel($stem),
                    'key_count' => count($fields),
                    'fields' => $fields,
                ];
            }
            if ($bundles === []) {
                continue;
            }
            $sections[] = array_merge($section, ['bundles' => $bundles]);
        }

        return [
            'sections' => $sections,
            'languages' => $languages,
            'statusMap' => $statusMap,
            'currentLang' => $languages[$locale] ?? null,
            'isMaster' => $locale === $masterLocale,
            'masterLocale' => $masterLocale,
        ];
    }

    /**
     * @return array{
     *   aiEnabled: bool,
     *   aiConfigUrl: string,
     *   aiTranslateUrl: string,
     *   aiTranslateSectionUrl: string,
     *   googleTranslateUrl: string,
     *   exportPromptUrl: string,
     *   importUrl: string,
     *   scope: string
     * }
     */
    protected function langUiAiViewData(string $scope, string $locale): array
    {
        $routeParams = ['locale' => $locale];
        $prefix = $scope === LangUiAdminScope::CONTRIBUTE
            ? 'admin.lang-ui.contribute'
            : 'admin.lang-ui';

        return [
            'scope' => $scope,
            'aiEnabled' => LangUiAiPrompts::isEnabled(),
            'aiConfigUrl' => route('admin.lang-ui.ai.config', ['scope' => $scope]),
            'aiTranslateUrl' => route($prefix . '.ai.translate-field', $routeParams),
            'aiTranslateSectionUrl' => route($prefix . '.ai.translate-section', $routeParams),
            'googleTranslateUrl' => route($prefix . '.google.translate-field', $routeParams),
            'exportPromptUrl' => route($prefix . '.export-prompt', $routeParams),
            'importUrl' => route($prefix . '.import', $routeParams),
        ];
    }

    protected function langUiFieldIsHtml(string $key, string $viText): bool
    {
        if (str_ends_with($key, '_html') || str_contains($key, 'timeline_title') || str_contains($key, '_title')) {
            return str_contains($viText, '<');
        }

        return str_contains($viText, '<') && preg_match('/<[a-z][\s\S]*>/i', $viText) === 1;
    }

    protected function langUiSectionLabel(string $scope, string $bundleStem): string
    {
        if ($scope === LangUiAdminScope::CONTRIBUTE) {
            return ContributeLangSections::bundleLabel($bundleStem);
        }

        return LandingLangSections::bundleLabel($bundleStem);
    }

    protected function langUiHeaderTheme(string $scope, string $sectionId): string
    {
        return $scope === LangUiAdminScope::CONTRIBUTE
            ? ContributeLangSections::headerTheme($sectionId)
            : LandingLangSections::headerTheme($sectionId);
    }

    /**
     * @return array<string, array<string, mixed>>
     */
    private function languagesForSwitcher(LangUiFileService $files): array
    {
        $nav = LanguageRegistry::forNav();
        $allowed = array_flip($files->allowedLocales());
        $out = [];

        foreach ($nav as $code => $row) {
            if (isset($allowed[$code])) {
                $out[$code] = $row;
            }
        }

        foreach ($files->allowedLocales() as $code) {
            if (! isset($out[$code])) {
                $out[$code] = [
                    'code' => $code,
                    'code_display' => strtoupper($code),
                    'name_native' => strtoupper($code),
                    'name_vi' => '',
                    'flag' => '',
                ];
            }
        }

        if (isset($out[LangUi::MASTER_LOCALE])) {
            $vi = $out[LangUi::MASTER_LOCALE];
            unset($out[LangUi::MASTER_LOCALE]);
            $out = [LangUi::MASTER_LOCALE => $vi] + $out;
        }

        return $out;
    }

    protected function saveLangUiBundle(
        Request $request,
        string $locale,
        string $scope,
        LangUiFileService $files,
    ): JsonResponse {
        $locale = strtolower($locale);

        if (! $files->isAllowedLocale($locale)) {
            return response()->json(['success' => false, 'message' => 'Locale không hợp lệ.'], 422);
        }

        $bundle = (string) $request->input('bundle', '');
        if (! $files->isAllowedBundle($bundle, $scope)) {
            return response()->json(['success' => false, 'message' => 'Section không hợp lệ.'], 422);
        }

        /** @var array<string, mixed> $keys */
        $keys = $request->input('keys', []);
        if (! is_array($keys)) {
            return response()->json(['success' => false, 'message' => 'Dữ liệu không hợp lệ.'], 422);
        }

        $payload = [];
        foreach ($keys as $key => $value) {
            if (is_string($key)) {
                $payload[$key] = is_string($value) ? $value : (string) $value;
            }
        }

        if ($payload === []) {
            return response()->json(['success' => false, 'message' => 'Không có trường nào để lưu.'], 422);
        }

        try {
            $files->writeBundle($locale, $bundle, $payload, $scope);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lưu thất bại: ' . $e->getMessage(),
            ], 500);
        }

        try {
            Artisan::call('config:clear');
        } catch (\Throwable) {
        }

        $sectionId = (string) $request->input('section_id', '');
        $label = $scope === LangUiAdminScope::CONTRIBUTE && $sectionId !== ''
            ? ContributeLangSections::sectionLabel($sectionId)
            : $this->langUiSectionLabel($scope, $bundle);

        return response()->json([
            'success' => true,
            'message' => 'Đã lưu ' . $label . ' (' . strtoupper($locale) . ').',
            'bundle' => LandingLangBundles::stem($bundle),
        ]);
    }
}
