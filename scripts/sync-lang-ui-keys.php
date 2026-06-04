<?php

declare(strict_types=1);

/**
 * Đồng bộ key mới từ master (vi) sang mọi locale — giữ bản dịch đã có, thêm key mới (copy vi).
 * Chạy: php scripts/sync-lang-ui-keys.php
 */

function lang_ui_sync_keys(?string $root = null): array
{
    $root = $root ?? dirname(__DIR__);

    $master = 'vi';
    $masterDir = $root . '/config/lang_ui/' . $master;
    $files = [
        'nav.php',
        'meta.php',
        'hero.php',
        'common.php',
        'what.php',
        'origin.php',
        'dispute.php',
        'opposition.php',
        'witnesses.php',
        'verdict.php',
        'sovereignty.php',
        'footer.php',
    ];

    if (! is_dir($masterDir)) {
        throw new RuntimeException("Missing master: {$masterDir}");
    }

    $escPhp = static fn (string $s): string => str_replace(['\\', '\''], ['\\\\', '\\\''], $s);

    $exportBundle = static function (array $data) use ($escPhp): string {
        $lines = ["<?php\n\n", 'return [' . "\n"];
        foreach ($data as $key => $value) {
            $lines[] = "    '" . $key . "' => '" . $escPhp((string) $value) . "',\n";
        }
        $lines[] = "];\n";

        return implode('', $lines);
    };

    $updatedLocales = 0;
    $updatedFiles = 0;

    foreach (glob($root . '/config/lang_ui/*', GLOB_ONLYDIR) ?: [] as $dir) {
        $locale = basename($dir);
        if ($locale === '' || $locale === $master) {
            continue;
        }

        $localeChanged = false;
        foreach ($files as $file) {
            $masterPath = $masterDir . '/' . $file;
            if (! is_file($masterPath)) {
                continue;
            }
            /** @var array<string, string> $masterData */
            $masterData = require $masterPath;
            $targetPath = $dir . '/' . $file;
            $targetData = is_file($targetPath) ? (require $targetPath) : [];
            if (! is_array($targetData)) {
                $targetData = [];
            }

            $merged = [];
            $changed = false;
            foreach ($masterData as $key => $masterVal) {
                if (array_key_exists($key, $targetData) && trim((string) $targetData[$key]) !== '') {
                    $merged[$key] = (string) $targetData[$key];
                } else {
                    $merged[$key] = (string) $masterVal;
                    $changed = true;
                }
            }

            if ($changed || count($merged) !== count($targetData)) {
                file_put_contents($targetPath, $exportBundle($merged));
                $updatedFiles++;
                $localeChanged = true;
            }
        }

        if ($localeChanged) {
            $updatedLocales++;
        }
    }

    return ['locales' => $updatedLocales, 'files' => $updatedFiles, 'master' => $master];
}

if (PHP_SAPI === 'cli' && realpath($_SERVER['SCRIPT_FILENAME'] ?? '') === realpath(__FILE__)) {
    $result = lang_ui_sync_keys();
    echo "Synced keys from {$result['master']}: {$result['locales']} locales, {$result['files']} files updated.\n";
}
