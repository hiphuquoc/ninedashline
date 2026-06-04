<?php

declare(strict_types=1);

$path = dirname(__DIR__) . '/resources/views/welcome.blade.php';
$content = file_get_contents($path);
$before = $content;

$content = preg_replace('/\{!!\s*t\(([^)]+)\)\s*\}\}/', '{!! t($1) !!}', $content) ?? $content;

// Attribute: giữ escape (plain text / alt)
$lines = explode("\n", $content);
foreach ($lines as $i => $line) {
    if (preg_match('/\balt=(["\'])[^"\']*\{!!\s*t\(/', $line) === 1) {
        $lines[$i] = preg_replace('/\{!!\s*t\(([^)]+)\)\s*!!\}/', '{{ te($1) }}', $line) ?? $line;
    }
}

$content = implode("\n", $lines);
file_put_contents($path, $content);

$n = substr_count($before, "{!! t(") - substr_count($before, "{!! t(");
echo preg_match_all('/\{!!\s*t\([^)]+\)\s*\}\}/', $before, $m) ? count($m[0]) . " fixed closings\n" : "done\n";
