<?php

declare(strict_types=1);

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\LangUiAiTranslationService;
use App\Services\LangUiGoogleTranslateService;
use App\Support\LangUi;
use App\Support\LangUiAdminScope;
use App\Support\LangUiAiPrompts;
use App\Support\LangUiTranslationImport;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LangUiAiController extends Controller
{
    public function __construct(
        private readonly LangUiAiTranslationService $ai,
        private readonly LangUiGoogleTranslateService $google,
    ) {}

    public function config(Request $request): JsonResponse
    {
        $scope = $this->normalizeScope((string) $request->query('scope', LangUiAdminScope::LANDING));

        return response()->json([
            'success' => true,
            'data' => $this->ai->clientConfig($scope),
        ]);
    }

    public function translateField(Request $request, string $locale): JsonResponse
    {
        if (! LangUiAiPrompts::isEnabled()) {
            return $this->aiDisabled();
        }

        return $this->wrapFieldTranslate($request, $locale, function (string $scope, string $sectionId, string $key, string $model, ?string $prompt) use ($request, $locale) {
            return $this->ai->translateField($scope, $locale, $sectionId, $key, $model, $prompt, $request->boolean('debug'));
        });
    }

    public function translateSection(Request $request, string $locale): JsonResponse
    {
        if (! LangUiAiPrompts::isEnabled()) {
            return $this->aiDisabled();
        }

        $locale = strtolower($locale);
        $scope = $this->normalizeScope((string) $request->input('scope', LangUiAdminScope::LANDING));
        $sectionId = trim((string) $request->input('section_id', ''));

        if ($sectionId === '') {
            return response()->json(['success' => false, 'message' => 'Thiếu section_id.'], 422);
        }

        $model = trim((string) $request->input('model', ''));
        if ($model === '') {
            $model = LangUiAiPrompts::defaultModel();
        }

        try {
            $out = $this->ai->translateSectionByExportPrompt(
                $scope,
                $locale,
                $sectionId,
                $model,
                $this->promptFromRequest($request),
                $request->boolean('debug'),
            );

            return response()->json(['success' => true, 'data' => $out]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function translateFieldGoogle(Request $request, string $locale): JsonResponse
    {
        $locale = strtolower($locale);
        if ($locale === LangUi::MASTER_LOCALE) {
            return response()->json(['success' => false, 'message' => 'Không dịch cho locale master (' . LangUi::MASTER_LOCALE . ').'], 422);
        }

        $scope = $this->normalizeScope((string) $request->input('scope', LangUiAdminScope::LANDING));
        $sectionId = trim((string) $request->input('section_id', ''));
        $key = trim((string) $request->input('key', ''));

        if ($sectionId === '' || $key === '') {
            return response()->json(['success' => false, 'message' => 'Thiếu section_id hoặc key.'], 422);
        }

        try {
            $jobs = $this->ai->sectionJobs($scope, $locale, $sectionId, [$key]);
            if (! isset($jobs[$key])) {
                return response()->json(['success' => false, 'message' => 'Key không có nội dung master (vi).'], 404);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'key' => $key,
                    'translated' => $this->google->translate($jobs[$key], $locale),
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    public function exportExternalPrompt(Request $request, string $locale): JsonResponse
    {
        $locale = strtolower($locale);
        $scope = $this->normalizeScope((string) $request->input('scope', $request->query('scope', LangUiAdminScope::LANDING)));
        $sectionId = trim((string) $request->input('section_id', $request->query('section_id', '')));

        if ($sectionId === '') {
            return response()->json(['success' => false, 'message' => 'Thiếu section_id.'], 422);
        }

        $sources = $this->ai->sectionJobs($scope, $locale, $sectionId, null);
        $customPrompt = trim((string) $request->input('field_prompt_vi', ''));

        $prompt = LangUiAiPrompts::buildExternalExportPrompt(
            $scope,
            $sectionId,
            $locale,
            $sources,
            $customPrompt !== '' ? $customPrompt : null,
        );

        return response()->json([
            'success' => true,
            'data' => [
                'prompt' => $prompt,
                'keys' => array_keys($sources),
                'key_count' => count($sources),
            ],
        ]);
    }

    public function importTranslations(Request $request, string $locale): JsonResponse
    {
        $locale = strtolower($locale);
        $scope = $this->normalizeScope((string) $request->input('scope', LangUiAdminScope::LANDING));
        $sectionId = trim((string) $request->input('section_id', ''));

        if ($sectionId === '') {
            return response()->json(['success' => false, 'message' => 'Thiếu section_id.'], 422);
        }

        try {
            $allowed = array_keys($this->ai->sectionJobs($scope, $locale, $sectionId, null));
            /** @var mixed $payloadMap */
            $payloadMap = $request->input('payload_map');
            if (is_array($payloadMap)) {
                $map = LangUiTranslationImport::fromDecoded($payloadMap, $allowed);
            } else {
                $raw = (string) $request->input('payload', '');
                $map = LangUiTranslationImport::parse($raw, $allowed);
            }
            $skipped = array_values(array_diff($allowed, array_keys($map)));
            $warnings = LangUiTranslationImport::warnings($map);

            return response()->json([
                'success' => true,
                'data' => [
                    'translated' => $map,
                    'imported' => count($map),
                    'skipped_keys' => $skipped,
                    'warnings' => $warnings,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 422);
        }
    }

    /**
     * @param callable(string, string, string, string, ?string): array{translated: string, debug?: array<string, mixed>} $runner
     */
    private function wrapFieldTranslate(Request $request, string $locale, callable $runner): JsonResponse
    {
        $locale = strtolower($locale);
        $scope = $this->normalizeScope((string) $request->input('scope', LangUiAdminScope::LANDING));
        $sectionId = trim((string) $request->input('section_id', ''));
        $key = trim((string) $request->input('key', ''));

        if ($sectionId === '' || $key === '') {
            return response()->json(['success' => false, 'message' => 'Thiếu section_id hoặc key.'], 422);
        }

        try {
            $out = $runner(
                $scope,
                $sectionId,
                $key,
                (string) $request->input('model', ''),
                $this->promptFromRequest($request),
            );

            return response()->json([
                'success' => true,
                'data' => [
                    'key' => $key,
                    'translated' => $out['translated'],
                    'debug' => $out['debug'] ?? null,
                ],
            ]);
        } catch (\Throwable $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 400);
        }
    }

    private function promptFromRequest(Request $request): ?string
    {
        $text = trim((string) $request->input('prompt_template_text', $request->input('field_prompt_vi', '')));

        return $text !== '' ? $text : null;
    }

    private function normalizeScope(string $scope): string
    {
        return LangUiAdminScope::isValid($scope) ? $scope : LangUiAdminScope::LANDING;
    }

    private function aiDisabled(): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => 'AI chưa bật. Cấu hình AI_ENABLED=true và API key trong .env',
        ], 503);
    }
}
