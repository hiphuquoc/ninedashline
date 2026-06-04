<?php

declare(strict_types=1);

namespace App\Services;

use App\Support\LangUi;
use App\Support\LangUiAdminScope;
use App\Support\LandingLangBundles;
use InvalidArgumentException;
use RuntimeException;

final class LangUiFileService
{
    public function masterLocale(): string
    {
        return LangUi::MASTER_LOCALE;
    }

    /** @return list<string> */
    public function allowedLocales(): array
    {
        $codes = [];
        foreach (glob(config_path('lang_ui/*'), GLOB_ONLYDIR) ?: [] as $dir) {
            $code = basename($dir);
            if ($code !== '') {
                $codes[] = $code;
            }
        }
        sort($codes);

        return $codes;
    }

    public function isAllowedLocale(string $locale): bool
    {
        $locale = strtolower($locale);

        return in_array($locale, $this->allowedLocales(), true);
    }

    /** @return list<string> */
    public function allowedBundles(string $scope = LangUiAdminScope::LANDING): array
    {
        return LangUiAdminScope::bundleStems($scope);
    }

    public function isAllowedBundle(string $bundle, string $scope = LangUiAdminScope::LANDING): bool
    {
        $bundle = LandingLangBundles::stem($bundle);

        return in_array($bundle, LangUiAdminScope::bundleStems($scope), true);
    }

    /**
     * @return array<string, string>
     */
    public function readBundle(string $locale, string $bundle, string $scope = LangUiAdminScope::LANDING): array
    {
        $path = $this->bundlePath($locale, $bundle, $scope);
        if (! is_file($path)) {
            return [];
        }

        /** @var array<string, string> $data */
        $data = require $path;

        return is_array($data) ? $data : [];
    }

    /**
     * @param array<string, string> $values Chỉ key đã có trong master vi.
     */
    public function writeBundle(string $locale, string $bundle, array $values, string $scope = LangUiAdminScope::LANDING): void
    {
        $locale = strtolower($locale);
        $bundle = LandingLangBundles::stem($bundle);

        if (! $this->isAllowedLocale($locale)) {
            throw new InvalidArgumentException('Locale không hợp lệ.');
        }
        if (! $this->isAllowedBundle($bundle, $scope)) {
            throw new InvalidArgumentException('Bundle không hợp lệ.');
        }

        $master = $this->readBundle($this->masterLocale(), $bundle, $scope);
        if ($master === []) {
            throw new RuntimeException('Không tìm thấy bản gốc tiếng Việt.');
        }

        $merged = $this->readBundle($locale, $bundle, $scope);
        foreach ($values as $key => $value) {
            if (! array_key_exists($key, $master)) {
                continue;
            }
            $merged[$key] = (string) $value;
        }

        $ordered = [];
        foreach (array_keys($master) as $key) {
            $ordered[$key] = $merged[$key] ?? '';
        }

        $dir = config_path('lang_ui/' . $locale);
        if (! is_dir($dir) && ! mkdir($dir, 0775, true) && ! is_dir($dir)) {
            throw new RuntimeException('Không tạo được thư mục locale: ' . $dir);
        }

        $path = $this->bundlePath($locale, $bundle, $scope);
        $written = @file_put_contents($path, $this->exportPhpArray($ordered));
        if ($written === false) {
            throw new RuntimeException(self::writeFailureMessage($path));
        }
    }

    public function localeFillRatio(string $locale): float
    {
        $master = LangUi::forLocale($this->masterLocale());
        if ($master === []) {
            return 0.0;
        }

        $target = LangUi::forLocale($locale);
        $filled = 0;
        foreach ($master as $key => $vi) {
            $val = trim((string) ($target[$key] ?? ''));
            if ($val !== '' && ($locale === $this->masterLocale() || $val !== trim((string) $vi))) {
                $filled++;
            }
        }

        return $filled / count($master);
    }

    public function localeLooksTranslated(string $locale): bool
    {
        if ($locale === $this->masterLocale()) {
            return true;
        }

        return $this->localeFillRatio($locale) >= 0.85 || LangUi::hasLocale($locale);
    }

    private function bundlePath(string $locale, string $bundle, string $scope): string
    {
        $bundle = LandingLangBundles::stem($bundle);
        if (! $this->isAllowedBundle($bundle, $scope)) {
            throw new InvalidArgumentException('Bundle không hợp lệ: ' . $bundle);
        }

        return config_path('lang_ui/' . strtolower($locale) . '/' . $bundle . '.php');
    }

    /**
     * @param array<string, string> $data
     */
    private function exportPhpArray(array $data): string
    {
        $lines = ["<?php\n\n", 'return [' . "\n"];
        foreach ($data as $key => $value) {
            $lines[] = "    '" . $key . "' => '" . $this->escPhpSingle((string) $value) . "',\n";
        }
        $lines[] = "];\n";

        return implode('', $lines);
    }

    private function escPhpSingle(string $s): string
    {
        return str_replace(['\\', '\''], ['\\\\', '\\\''], $s);
    }

    private static function writeFailureMessage(string $path): string
    {
        $last = error_get_last();
        $detail = is_array($last) ? (string) ($last['message'] ?? '') : '';
        $msg = 'Không ghi được file ngôn ngữ: ' . $path;
        if ($detail !== '') {
            $msg .= ' — ' . $detail;
        }
        if (str_contains($detail, 'Permission denied')) {
            $msg .= ' — Web server (PHP-FPM) cần quyền ghi thư mục config/lang_ui/. '
                . 'Trên aaPanel/宝塔: chown -R www:www config/lang_ui && chmod -R ug+rwX config/lang_ui '
                . '(xem docs/DEPLOY.md).';
        }

        return $msg;
    }
}
