<?php

declare(strict_types=1);

namespace App\Services;

use App\Support\LangUi;
use App\Support\LangUiAdminScope;
use Illuminate\Http\Request;

final class LangUiWriteTestService
{
    public function __construct(
        private readonly LangUiFileService $files,
    ) {}

    /**
     * @return array{
     *   site_host: string,
     *   site_path: string,
     *   php_user: string,
     *   fix_commands: list<string>,
     *   fix_commands_text: string,
     *   checks: list<array<string, mixed>>,
     *   all_ok: bool,
     *   has_permission_error: bool
     * }
     */
    public function runAll(?Request $request = null): array
    {
        $request ??= request();
        $sitePath = base_path();
        $siteHost = $request->getHost() ?: (string) parse_url((string) config('app.url'), PHP_URL_HOST);
        $fixCommands = $this->fixCommands($sitePath);

        $checks = [
            $this->probeLangUiRoot(),
            $this->probeBundleWrite(LangUiAdminScope::LANDING, 'en', 'nav'),
            $this->probeBundleWrite(LangUiAdminScope::CONTRIBUTE, 'en', 'chung_suc'),
        ];

        $allOk = true;
        $hasPermissionError = false;
        foreach ($checks as $check) {
            if (! empty($check['skipped'])) {
                continue;
            }
            if (empty($check['ok'])) {
                $allOk = false;
            }
            if (! empty($check['is_permission_error'])) {
                $hasPermissionError = true;
            }
        }

        return [
            'site_host' => $siteHost,
            'site_path' => $sitePath,
            'php_user' => (string) (get_current_user() ?: 'www'),
            'fix_commands' => $fixCommands,
            'fix_commands_text' => implode("\n", $fixCommands),
            'checks' => $checks,
            'all_ok' => $allOk,
            'has_permission_error' => $hasPermissionError,
        ];
    }

    /** @return list<string> */
    public function fixCommands(string $sitePath): array
    {
        return [
            'cd ' . $sitePath,
            'chown -R www:www config/lang_ui',
            'chmod -R ug+rwX config/lang_ui',
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function probeLangUiRoot(): array
    {
        $dir = config_path('lang_ui');
        $probe = $dir . '/.admin_write_probe_' . uniqid('', true);

        if (! is_dir($dir)) {
            return [
                'id' => 'lang_ui_root',
                'label' => 'Thư mục config/lang_ui',
                'ok' => false,
                'path' => $dir,
                'message' => 'Thư mục không tồn tại.',
                'is_permission_error' => false,
            ];
        }

        $written = @file_put_contents($probe, "<?php\n// admin write probe\n");
        if ($written === false) {
            $detail = (string) (error_get_last()['message'] ?? '');

            return [
                'id' => 'lang_ui_root',
                'label' => 'Thư mục config/lang_ui',
                'ok' => false,
                'path' => $dir,
                'message' => 'Không ghi được file thử: ' . $probe . ($detail !== '' ? ' — ' . $detail : ''),
                'is_permission_error' => $this->looksLikePermissionError($detail),
            ];
        }

        @unlink($probe);

        return [
            'id' => 'lang_ui_root',
            'label' => 'Thư mục config/lang_ui',
            'ok' => true,
            'path' => $dir,
            'message' => 'Ghi file thử OK.',
            'is_permission_error' => false,
        ];
    }

    /**
     * @return array<string, mixed>
     */
    private function probeBundleWrite(string $scope, string $locale, string $bundle): array
    {
        $locale = strtolower($locale);
        $scopeLabel = $scope === LangUiAdminScope::CONTRIBUTE ? 'Chung sức' : 'Landing';
        $label = $scopeLabel . ' · ' . strtoupper($locale) . ' · ' . $bundle . '.php';
        $path = config_path('lang_ui/' . $locale . '/' . $bundle . '.php');

        if (! $this->files->isAllowedBundle($bundle, $scope)) {
            return [
                'id' => $scope . '_' . $locale . '_' . $bundle,
                'label' => $label,
                'scope' => $scope,
                'locale' => $locale,
                'bundle' => $bundle,
                'ok' => false,
                'skipped' => true,
                'path' => $path,
                'message' => 'Bundle không áp dụng cho site này.',
                'is_permission_error' => false,
            ];
        }

        if (! $this->files->isAllowedLocale($locale)) {
            return [
                'id' => $scope . '_' . $locale . '_' . $bundle,
                'label' => $label,
                'scope' => $scope,
                'locale' => $locale,
                'bundle' => $bundle,
                'ok' => false,
                'skipped' => true,
                'path' => $path,
                'message' => 'Locale ' . strtoupper($locale) . ' chưa có thư mục config/lang_ui.',
                'is_permission_error' => false,
            ];
        }

        $master = $this->files->readBundle(LangUi::MASTER_LOCALE, $bundle, $scope);
        if ($master === []) {
            return [
                'id' => $scope . '_' . $locale . '_' . $bundle,
                'label' => $label,
                'scope' => $scope,
                'locale' => $locale,
                'bundle' => $bundle,
                'ok' => false,
                'skipped' => true,
                'path' => $path,
                'message' => 'Chưa có bản gốc vi/' . $bundle . '.php.',
                'is_permission_error' => false,
            ];
        }

        $key = (string) array_key_first($master);
        $target = $this->files->readBundle($locale, $bundle, $scope);
        $value = (string) ($target[$key] ?? $master[$key]);

        try {
            $this->files->writeBundle($locale, $bundle, [$key => $value], $scope);

            return [
                'id' => $scope . '_' . $locale . '_' . $bundle,
                'label' => $label,
                'scope' => $scope,
                'locale' => $locale,
                'bundle' => $bundle,
                'ok' => true,
                'path' => $path,
                'message' => 'Ghi và lưu file ngôn ngữ OK (key thử: ' . $key . ').',
                'is_permission_error' => false,
            ];
        } catch (\Throwable $e) {
            $msg = $e->getMessage();

            return [
                'id' => $scope . '_' . $locale . '_' . $bundle,
                'label' => $label,
                'scope' => $scope,
                'locale' => $locale,
                'bundle' => $bundle,
                'ok' => false,
                'path' => $path,
                'message' => $msg,
                'is_permission_error' => $this->looksLikePermissionError($msg),
            ];
        }
    }

    private function looksLikePermissionError(string $message): bool
    {
        $m = strtolower($message);

        return str_contains($m, 'permission denied')
            || str_contains($m, 'không ghi được file ngôn ngữ')
            || str_contains($m, 'không tạo được thư mục locale');
    }
}
