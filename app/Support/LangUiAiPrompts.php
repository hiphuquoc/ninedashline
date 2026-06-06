<?php

declare(strict_types=1);

namespace App\Support;

use Illuminate\Support\Facades\File;

final class LangUiAiPrompts
{
    public static function isEnabled(): bool
    {
        return (bool) config('ai.enabled');
    }

    /** @return list<string> */
    public static function models(): array
    {
        $models = config('ai.models', []);

        return is_array($models) ? array_values($models) : [];
    }

    public static function defaultModel(): string
    {
        $models = self::models();
        $def = (string) config('ai.default_model', '');

        return $def !== '' ? $def : ($models[0] ?? 'openai:gpt-4o-mini');
    }

    public static function targetLanguageName(string $locale): string
    {
        $lang = LanguageRegistry::forNav()[$locale] ?? null;

        if (is_array($lang)) {
            $vi = trim((string) ($lang['name_vi'] ?? ''));
            $native = trim((string) ($lang['name_native'] ?? ''));

            return $vi !== '' ? $vi . ($native !== '' && $native !== $vi ? ' (' . $native . ')' : '') : ($native ?: strtoupper($locale));
        }

        return strtoupper($locale);
    }

    /**
     * @return array{label: string, field_prompt_vi: string}|null
     */
    public static function sectionMeta(string $scope, string $sectionId): ?array
    {
        $sections = config('lang_ui_ai.scopes.' . $scope . '.' . $sectionId);

        return is_array($sections) ? $sections : null;
    }

    /** Prompt tiếng Việt mặc định cho section (dịch 1 trường qua API). */
    public static function defaultFieldPromptVi(string $scope, string $sectionId): string
    {
        $meta = self::sectionMeta($scope, $sectionId);
        $sectionHint = is_array($meta) ? trim((string) ($meta['field_prompt_vi'] ?? '')) : '';
        $base = trim((string) config('lang_ui_ai.field_prompt_base_vi', ''));
        $suffix = trim((string) config('lang_ui_ai.field_prompt_suffix', ''));

        return trim($base . "\n\n" . $sectionHint . "\n" . $suffix);
    }

    /**
     * Prompt section đã thay token — dùng cho dịch ngoài (không còn [locale], [source], …).
     */
    public static function resolvedSectionPromptVi(
        string $scope,
        string $sectionId,
        string $locale,
        ?string $customTemplate = null,
    ): string {
        $template = trim($customTemplate ?? '');
        if ($template === '') {
            $meta = self::sectionMeta($scope, $sectionId);
            $sectionHint = is_array($meta) ? trim((string) ($meta['field_prompt_vi'] ?? '')) : '';
            $base = trim((string) config('lang_ui_ai.field_prompt_base_vi', ''));
            $template = trim($base . "\n\n" . $sectionHint);
        } else {
            $template = preg_replace(
                '/\n---\s*\nNgữ cảnh section:.*$/s',
                '',
                $template,
            ) ?? $template;
            $template = preg_replace(
                '/\nNội dung tiếng Việt \(dịch sang.*?\[source\]\s*$/s',
                '',
                $template,
            ) ?? $template;
        }

        return self::applyTokens($template, [
            'locale' => $locale,
            'target_language' => self::targetLanguageName($locale),
            'section' => self::sectionLabel($scope, $sectionId),
            'key' => '(từng key trong JSON danh sách bên dưới)',
            'source' => '(từng value tiếng Việt trong JSON)',
        ]);
    }

    public static function compileFieldPrompt(
        string $scope,
        string $sectionId,
        string $locale,
        string $targetLanguage,
        string $source,
        string $key,
        ?string $customTemplate = null,
    ): string {
        $template = trim($customTemplate ?? '');
        if ($template === '') {
            $template = self::defaultFieldPromptVi($scope, $sectionId);
        }

        if (! str_contains($template, '[source]')) {
            $template .= "\n\n[source]";
        }

        return self::applyTokens($template, [
            'source' => $source,
            'locale' => $locale,
            'target_language' => $targetLanguage,
            'section' => self::sectionLabel($scope, $sectionId),
            'key' => $key,
        ]);
    }

    public static function systemPrompt(): string
    {
        return 'You are an expert translator. Follow the user prompt exactly. Return only the translated string.';
    }

    public static function sectionLabel(string $scope, string $sectionId): string
    {
        $meta = self::sectionMeta($scope, $sectionId);

        if (is_array($meta) && ($meta['label'] ?? '') !== '') {
            return (string) $meta['label'];
        }

        if ($scope === LangUiAdminScope::CONTRIBUTE) {
            return ContributeLangSections::sectionLabel($sectionId);
        }

        foreach (LandingLangSections::forAdmin() as $section) {
            if ($section['id'] === $sectionId) {
                return $section['label'];
            }
        }

        return $sectionId;
    }

    /**
     * Prompt hoàn chỉnh để dán vào chat AI ngoài — không còn placeholder [locale], …
     *
     * @param array<string, string> $sources key => vi text
     */
    public static function buildExternalExportPrompt(
        string $scope,
        string $sectionId,
        string $locale,
        array $sources,
        ?string $customFieldPromptVi = null,
    ): string {
        $locale = strtolower($locale);
        $targetLanguage = self::targetLanguageName($locale);
        $sectionLabel = self::sectionLabel($scope, $sectionId);
        $sectionRules = self::resolvedSectionPromptVi($scope, $sectionId, $locale, $customFieldPromptVi);
        $geoBlock = self::geoNamesBlockForLocale($locale);
        $hrefBlock = EcosystemSites::promptHrefRulesForLocale($locale);
        $sourcesJson = json_encode($sources, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES) ?: '{}';
        $keyList = implode(', ', array_keys($sources));

        return <<<PROMPT
Bạn là biên dịch chuyên môn cho ninedashline.dev (giáo dục về tranh chấp Đường Lưỡi Bò / Nine-Dash Line trên Biển Đông).

=== NHIỆM VỤ ===
Dịch ĐỒNG LOẠT các chuỗi giao diện (UI) từ tiếng Việt sang ngôn ngữ đích bên dưới.
Mỗi mục trong JSON nguồn là một key — bạn phải dịch value, GIỮ NGUYÊN tên key.

=== NGÔN NGỮ ĐÍCH ===
- Tên: {$targetLanguage}
- Mã locale trên site: {$locale}

=== SECTION ĐANG DỊCH ===
{$sectionLabel} (id: {$sectionId})

=== TÊN ĐỊA DANH BẮT BUỘC (dùng đúng trong mọi chuỗi dịch) ===
{$geoBlock}

=== LIÊN KẾT HỆ SINH THÁI (chỉ thuộc tính href) ===
{$hrefBlock}

=== QUY TẮC CHUNG + HƯỚNG DẪN SECTION ===
{$sectionRules}

=== YÊU CẦU ĐẦU RA (đọc kỹ) ===
1. Trả về DUY NHẤT một JSON object hợp lệ, UTF-8.
2. Mỗi key trong danh sách nguồn phải có đúng một key tương ứng trong JSON trả về: {$keyList}
3. Giá trị mỗi key = bản dịch sang {$targetLanguage}; không thêm key mới, không bỏ key.
4. Dịch phần văn bản hiển thị; GIỮ NGUYÊN cấu trúc HTML (tên thẻ, thuộc tính, class CSS), placeholder :year, :hoangsa, :truongsa, :paracel, :spratly, :ninedashline, :count, xuống dòng. URL trong href site hệ sinh thái: bắt buộc /{$locale} sau domain (xem khối LIÊN KẾT HỆ SINH THÁI).
5. KHÔNG bọc JSON trong markdown, KHÔNG giải thích trước/sau, KHÔNG thêm comment.
6. JSON hợp lệ — BẮT BUỘC escape dấu `"` bên trong mỗi value bằng `\"` (backslash + dấu ngoặc kép). KHÔNG bỏ ký tự `\` trước `"`.
   - SAI (admin import sẽ vỡ, HTML footer/legal không hiển thị):
     "footer_legal": "<a href=":hoangsa" target="_blank">..."
   - ĐÚNG (dịch chữ trong HTML, giữ thẻ + escape JSON):
     "footer_legal": "<a href=\":hoangsa\" target=\"_blank\" rel=\"noopener noreferrer\">..."
   - Mọi `"` nằm TRONG chuỗi value (href, target, rel, …) phải thành `\"` trong JSON trả về.
   - Backslash thật trong text (nếu có) viết `\\` trong JSON.
7. Tự kiểm tra: chạy json_decode được; mỗi value có HTML vẫn còn đủ thẻ đóng (`</a>`, `</em>`, …) và placeholder :hoangsa, :truongsa, …

=== DANH SÁCH NGUỒN TIẾNG VIỆT (JSON — dịch từng value) ===
{$sourcesJson}
PROMPT;
    }

    public static function geoNamesBlockForLocale(string $locale): string
    {
        return GeoNames::promptBlockForLocale($locale);
    }

    /**
     * @param array<string, string> $tokens
     */
    public static function applyTokens(string $text, array $tokens): string
    {
        $map = [];
        foreach ($tokens as $name => $value) {
            $map['[' . $name . ']'] = $value;
        }

        return strtr($text, $map);
    }

    /**
     * @return list<array{id: string, label: string, field_prompt_vi: string}>
     */
    public static function sectionsForScope(string $scope): array
    {
        $map = config('lang_ui_ai.scopes.' . $scope, []);
        if (! is_array($map)) {
            $map = [];
        }

        $sectionDefs = match ($scope) {
            LangUiAdminScope::CONTRIBUTE => ContributeLangSections::forAdmin(),
            default => LandingLangSections::forAdmin(),
        };

        $out = [];
        foreach ($sectionDefs as $section) {
            $id = (string) $section['id'];
            $row = is_array($map[$id] ?? null) ? $map[$id] : [];
            $out[] = [
                'id' => $id,
                'label' => (string) ($row['label'] ?? $section['label']),
                'field_prompt_vi' => self::defaultFieldPromptVi($scope, $id),
            ];
        }

        return $out;
    }

    public static function sanitizeModelOutput(string $text): string
    {
        $text = trim($text);
        if (preg_match('/^["\'](.*)["\']$/us', $text, $m) === 1) {
            $text = trim($m[1]);
        }
        if (str_starts_with($text, '```')) {
            $text = preg_replace('/^```[a-z]*\s*/i', '', $text) ?? $text;
            $text = preg_replace('/\s*```$/', '', $text) ?? $text;
        }

        return trim($text);
    }
}
