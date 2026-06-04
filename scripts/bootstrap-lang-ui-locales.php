<?php

/**
 * Tạo / cập nhật config/lang_ui/{locale}/*.php — đồng bộ key từ master (vi).
 * Chạy: php scripts/bootstrap-lang-ui-locales.php
 */

declare(strict_types=1);

$root = dirname(__DIR__);
require $root . '/vendor/autoload.php';

$app = require $root . '/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

require __DIR__ . '/sync-lang-ui-keys.php';

$master = \App\Support\LangUi::MASTER_LOCALE;
$masterDir = $root . '/config/lang_ui/' . $master;
$files = \App\Support\LandingLangBundles::files();

if (! is_dir($masterDir)) {
    fwrite(STDERR, "Missing master dir: {$masterDir}\n");
    exit(1);
}

$list = config('language.list', []);
$created = 0;

foreach ($list as $code => $row) {
    if (! ($row['is_active'] ?? true)) {
        continue;
    }
    if ($code === $master) {
        continue;
    }

    $targetDir = $root . '/config/lang_ui/' . $code;
    if (! is_dir($targetDir)) {
        mkdir($targetDir, 0755, true);
        $created++;
    }

    foreach ($files as $file) {
        $src = $masterDir . '/' . $file;
        $dst = $targetDir . '/' . $file;
        if (! is_file($src)) {
            continue;
        }
        if (! is_file($dst)) {
            copy($src, $dst);
        }
    }
}

$result = lang_ui_sync_keys($root);

echo "Bootstrap lang_ui from {$master}: new dirs {$created}; sync {$result['locales']} locales, {$result['files']} files.\n";
