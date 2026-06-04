<?php

declare(strict_types=1);

namespace App\Services;

use App\Services\Ai\AiGatewayService;
use App\Support\ContributeLangSections;
use App\Support\LangUi;
use App\Support\LangUiAdminScope;
use App\Support\LangUiAiPrompts;
use App\Support\LangUiTranslationImport;
use App\Support\LandingLangSections;
use App\Support\LanguageRegistry;
use RuntimeException;

final class LangUiAiTranslationService
{
    public function __construct(
        private readonly LangUiFileService $files,
        private readonly AiGatewayService $ai,
    ) {}

    /**
     * @return array{
     *   enabled: bool,
     *   models: list<string>,
     *   default_model: string,
     *   sections: list<array{id: string, label: string, prompt: string}>
     * }
     */
    public function clientConfig(string $scope): array
    {
        return [
            'enabled' => LangUiAiPrompts::isEnabled(),
            'models' => LangUiAiPrompts::models(),
            'default_model' => LangUiAiPrompts::defaultModel(),
            'sections' => LangUiAiPrompts::sectionsForScope($scope),
        ];
    }

    /**
     * @return array{translated: string, debug?: array<string, mixed>}
     */
    public function translateField(
        string $scope,
        string $locale,
        string $sectionId,
        string $key,
        string $model,
        ?string $promptTemplate,
        bool $debug = false,
    ): array {
        $locale = strtolower($locale);
        if ($locale === LangUi::MASTER_LOCALE) {
            throw new RuntimeException('Không dịch AI cho locale master (' . LangUi::MASTER_LOCALE . ').');
        }

        $stem = $this->bundleForKey($scope, $key);
        $masterVi = $this->files->readBundle(LangUi::MASTER_LOCALE, $stem, $scope);
        if (! array_key_exists($key, $masterVi)) {
            throw new RuntimeException('Key không tồn tại: ' . $key);
        }
        $source = (string) $masterVi[$key];
        if (trim($source) === '') {
            return ['translated' => ''];
        }

        return $this->translateText($scope, $sectionId, $locale, $key, $source, $model, $promptTemplate, $debug);
    }

    /**
     * @param list<string>|null $keys null = all keys in section
     * @return array{results: array<string, string>, errors: array<string, string>}
     */
    public function translateSection(
        string $scope,
        string $locale,
        string $sectionId,
        ?array $keys,
        string $model,
        ?string $promptTemplate,
    ): array {
        $jobs = $this->sectionJobs($scope, $locale, $sectionId, $keys);
        $results = [];
        $errors = [];

        foreach ($jobs as $key => $source) {
            try {
                $out = $this->translateText($scope, $sectionId, $locale, $key, $source, $model, $promptTemplate, false);
                $results[$key] = $out['translated'];
            } catch (\Throwable $e) {
                $errors[$key] = $e->getMessage();
            }
        }

        return ['results' => $results, 'errors' => $errors];
    }

    /**
     * Dịch cả section một lần (prompt giống Copy Prompt) → map key => bản dịch.
     *
     * @return array{
     *   translated: array<string, string>,
     *   warnings: list<string>,
     *   imported: int,
     *   key_count: int,
     *   debug?: array<string, mixed>
     * }
     */
    public function translateSectionByExportPrompt(
        string $scope,
        string $locale,
        string $sectionId,
        string $model,
        ?string $customFieldPromptVi = null,
        bool $debug = false,
    ): array {
        $locale = strtolower($locale);
        if ($locale === LangUi::MASTER_LOCALE) {
            throw new RuntimeException('Không dịch AI cho locale master (' . LangUi::MASTER_LOCALE . ').');
        }

        $sources = $this->sectionJobs($scope, $locale, $sectionId, null);
        if ($sources === []) {
            throw new RuntimeException('Section không có trường VI cần dịch.');
        }

        $custom = $customFieldPromptVi !== null ? trim($customFieldPromptVi) : '';
        $userPrompt = LangUiAiPrompts::buildExternalExportPrompt(
            $scope,
            $sectionId,
            $locale,
            $sources,
            $custom !== '' ? $custom : null,
        );

        $messages = [
            ['role' => 'system', 'content' => LangUiAiPrompts::systemPrompt()],
            ['role' => 'user', 'content' => $userPrompt],
        ];

        $options = ['model' => $model];
        if ($debug || config('ai.debug')) {
            $options['debug'] = true;
        }

        $result = $this->ai->chat($messages, $options);
        $raw = LangUiAiPrompts::sanitizeModelOutput((string) ($result['content'] ?? ''));
        $allowed = array_keys($sources);
        $map = LangUiTranslationImport::parse($raw, $allowed);
        $warnings = LangUiTranslationImport::warnings($map);

        $out = [
            'translated' => $map,
            'warnings' => $warnings,
            'imported' => count($map),
            'key_count' => count($allowed),
        ];
        if (! empty($result['debug'])) {
            $out['debug'] = $result['debug'];
        }

        return $out;
    }

    /**
     * @param list<string>|null $keys
     * @return array<string, string> key => vi source
     */
    public function sectionJobs(string $scope, string $locale, string $sectionId, ?array $keys): array
    {
        $filter = $this->sectionKeyFilter($scope, $sectionId);
        $jobs = [];

        foreach ($this->sectionBundleStems($scope, $sectionId) as $stem) {
            $masterVi = $this->files->readBundle(LangUi::MASTER_LOCALE, $stem, $scope);
            foreach ($masterVi as $key => $viText) {
                if ($filter !== null && ! $filter($key)) {
                    continue;
                }
                if ($keys !== null && ! in_array($key, $keys, true)) {
                    continue;
                }
                if (trim($viText) === '') {
                    continue;
                }
                $jobs[$key] = $viText;
            }
        }

        return $jobs;
    }

    /**
     * @return array{translated: string, debug?: array<string, mixed>}
     */
    private function translateText(
        string $scope,
        string $sectionId,
        string $locale,
        string $key,
        string $source,
        string $model,
        ?string $promptTemplate,
        bool $debug,
    ): array {
        $lang = LanguageRegistry::forNav()[$locale] ?? null;
        $targetName = is_array($lang)
            ? ($lang['name_vi'] ?: $lang['name_native'] ?: strtoupper($locale))
            : strtoupper($locale);

        $userPrompt = LangUiAiPrompts::compileFieldPrompt(
            $scope,
            $sectionId,
            $locale,
            $targetName,
            $source,
            $key,
            $promptTemplate,
        );

        $messages = [
            ['role' => 'system', 'content' => LangUiAiPrompts::systemPrompt()],
            ['role' => 'user', 'content' => $userPrompt],
        ];

        $options = ['model' => $model];
        if ($debug || config('ai.debug')) {
            $options['debug'] = true;
        }

        $result = $this->ai->chat($messages, $options);
        $translated = LangUiAiPrompts::sanitizeModelOutput((string) ($result['content'] ?? ''));

        $out = ['translated' => $translated];
        if (! empty($result['debug'])) {
            $out['debug'] = $result['debug'];
        }

        return $out;
    }

    private function bundleForKey(string $scope, string $key): string
    {
        foreach ($this->files->allowedBundles($scope) as $stem) {
            $data = $this->files->readBundle(LangUi::MASTER_LOCALE, $stem, $scope);
            if (array_key_exists($key, $data)) {
                return $stem;
            }
        }

        throw new RuntimeException('Không xác định được bundle cho key: ' . $key);
    }

    /** @return list<string> */
    private function sectionBundleStems(string $scope, string $sectionId): array
    {
        if ($scope === LangUiAdminScope::CONTRIBUTE) {
            return ['chung_suc'];
        }

        foreach (LandingLangSections::forAdmin() as $section) {
            if ($section['id'] === $sectionId) {
                return $section['bundles'];
            }
        }

        return [];
    }

    /** @return callable(string): bool|null */
    private function sectionKeyFilter(string $scope, string $sectionId): ?callable
    {
        if ($scope !== LangUiAdminScope::CONTRIBUTE) {
            return null;
        }

        foreach (ContributeLangSections::forAdmin() as $section) {
            if ($section['id'] === $sectionId) {
                return $section['key_filter'];
            }
        }

        return null;
    }
}
