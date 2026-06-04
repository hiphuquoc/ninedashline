<?php

/**
 * Đồng bộ config/language.php từ wallsora.dev/config/language.php
 * Chạy: php scripts/build-language-config.php
 */

$root = dirname(__DIR__);
$wallsora = dirname($root) . '/wallsora.dev/config/language.php';
$flags = require $root . '/config/language_flags.php';

if (! is_file($wallsora)) {
    fwrite(STDERR, "Không tìm thấy: {$wallsora}\n");
    exit(1);
}

/** @var array<string, array<string, mixed>> $ws */
$ws = require $wallsora;

function englishLabel(array $row): string
{
    $native = (string) ($row['name_by_language'] ?? '');
    if (preg_match('/\(([^)]+)\)\s*$/u', $native, $m)) {
        return trim($m[1]);
    }

    return $native;
}

$hitourExtras = [
    'zh-cn' => [
        'name_native' => '简体中文',
        'name_english' => 'Chinese (Simplified)',
        'name_vi' => 'Tiếng Trung (Giản thể)',
        'flag_cc' => 'cn',
        'dir' => 'ltr',
        'sort' => 3,
        'is_active' => true,
        'is_default' => false,
    ],
    'zh-tw' => [
        'name_native' => '繁體中文',
        'name_english' => 'Chinese (Traditional)',
        'name_vi' => 'Tiếng Trung (Phồn thể)',
        'flag_cc' => 'tw',
        'dir' => 'ltr',
        'sort' => 4,
        'is_active' => true,
        'is_default' => false,
    ],
];

$list = [];
$order = 0;

foreach ($ws as $code => $row) {
    $order++;
    $list[$code] = [
        'code' => $code,
        'name_native' => (string) ($row['name_by_language'] ?? $code),
        'name_english' => englishLabel($row),
        'name_vi' => (string) ($row['name'] ?? ''),
        'flag_cc' => $flags[$code] ?? $code,
        'dir' => (string) ($row['dir'] ?? 'ltr'),
        'sort' => $code === 'en' ? 0 : ($code === 'vi' ? 2 : $order),
        'is_active' => true,
        'is_default' => $code === 'en',
    ];
}

foreach ($hitourExtras as $code => $extra) {
    if (! isset($list[$code])) {
        $list[$code] = array_merge(['code' => $code], $extra);
    }
}

unset($list['zh']);

$navUi = [
    'vi' => [
        'dialog_title' => 'Ngôn ngữ hiển thị',
        'language' => 'Ngôn ngữ',
        'display_hint' => count($list) . ' ngôn ngữ',
        'search_placeholder' => 'Tìm ngôn ngữ…',
        'cancel' => 'Hủy',
        'apply' => 'Áp dụng',
        'sound_off' => 'Bật âm thanh sóng biển',
        'sound_on' => 'Tắt âm thanh sóng biển',
        'sound_label_off' => 'Âm thanh',
        'sound_label_on' => 'Đang phát',
        'contribute' => 'Chia sẻ',
        'lang_trigger' => 'Chọn ngôn ngữ',
        'close' => 'Đóng',
        'no_results' => 'Không tìm thấy ngôn ngữ',
    ],
    'en' => [
        'dialog_title' => 'Display language',
        'language' => 'Language',
        'display_hint' => count($list) . ' languages',
        'search_placeholder' => 'Search languages…',
        'cancel' => 'Cancel',
        'apply' => 'Apply',
        'sound_off' => 'Turn on ocean ambience',
        'sound_on' => 'Turn off ocean ambience',
        'sound_label_off' => 'Sound',
        'sound_label_on' => 'Playing',
        'contribute' => 'Share',
        'lang_trigger' => 'Choose language',
        'close' => 'Close',
        'no_results' => 'No languages found',
    ],
];

$export = var_export([
    'default_code' => 'en',
    'fallback_code' => 'vi',
    'source' => 'wallsora.dev/config/language.php',
    'list' => $list,
    'nav_ui' => $navUi,
], true);

$php = <<<PHP
<?php

/**
 * Danh sách ngôn ngữ — đồng bộ từ wallsora.dev (+ zh-cn, zh-tw).
 * Cập nhật: php scripts/build-language-config.php
 */
return {$export};

PHP;

file_put_contents($root . '/config/language.php', $php);
echo 'Wrote ' . count($list) . " languages to config/language.php (default: en)\n";
