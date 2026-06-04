<?php

declare(strict_types=1);

/**
 * Dịch chuỗi (Google gtx) — admin /he-thong/ngon-ngu.
 * Master locale: vi (ninedashline.dev).
 */

const TRANSLATION_SOURCE_LOCALE = 'vi';
const TRANSLATION_CACHE_VERSION = 'ctx-v2-vi';

function localeUsesWordSpaces(string $locale): bool
{
    static $compact = ['zh-cn' => true, 'zh-tw' => true, 'ja' => true, 'ko' => true];

    return ! isset($compact[$locale]);
}

/** @return array{0: string, 1: array<string, string>} */
function protectPlaceholders(string $s): array
{
    $placeholders = [];
    $i = 0;
    $out = preg_replace_callback(
        '/:(count|year|hoangsa|truongsa|min|max)\b/',
        static function (array $m) use (&$placeholders, &$i): string {
            $token = '⟦PH' . ($i++) . '⟧';
            $placeholders[$token] = $m[0];

            return $token;
        },
        $s
    ) ?? $s;

    return [$out, $placeholders];
}

/** @return array{0: string, 1: array<string, string>} */
function protectHtml(string $s): array
{
    $placeholders = [];
    $i = 0;
    $out = preg_replace_callback('/<[^>]+>/', static function (array $m) use (&$placeholders, &$i): string {
        $token = '⟦' . ($i++) . '⟧';
        $placeholders[$token] = $m[0];

        return $token;
    }, $s) ?? $s;

    return [$out, $placeholders];
}

function padMarkupTokens(string $plain): string
{
    $plain = preg_replace('/([^\s⟦⟧])(⟦)/u', '$1 $2', $plain) ?? $plain;
    $plain = preg_replace('/(⟧)([^\s⟦⟧])/u', '$1 $2', $plain) ?? $plain;

    return $plain;
}

function restoreTokens(string $s, array $placeholders): string
{
    uksort($placeholders, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));
    foreach ($placeholders as $token => $value) {
        $s = str_replace($token, $value, $s);
    }

    return $s;
}

function restoreHtml(string $s, array $placeholders): string
{
    uksort($placeholders, static fn (string $a, string $b): int => strlen($b) <=> strlen($a));

    foreach ($placeholders as $token => $html) {
        if (str_contains($s, $token)) {
            $s = str_replace($token, $html, $s);
            continue;
        }
        if (preg_match('/⟦(\d+)⟧/', $token, $m) === 1) {
            $id = $m[1];
            $pattern = '/⟦\s*' . preg_quote($id, '/') . '\s*⟧/u';
            $s = preg_replace($pattern, $html, $s) ?? $s;
        }
    }

    return $s;
}

/** @return array{0: string, 1: string, 2: string} */
function splitOuterWhitespace(string $segment): array
{
    if (preg_match('/^(\s*)(.*?)(\s*)$/us', $segment, $m) !== 1) {
        return ['', $segment, ''];
    }

    return [$m[1], $m[2], $m[3]];
}

function joinGoogleTranslateParts(array $json, string $originalCore, string $locale): string
{
    $translated = '';
    if (is_array($json) && isset($json[0]) && is_array($json[0])) {
        foreach ($json[0] as $part) {
            if (is_array($part) && isset($part[0]) && is_string($part[0])) {
                $translated .= $part[0];
            }
        }
    }

    if ($translated === '' || ! localeUsesWordSpaces($locale)) {
        return $translated;
    }

    $srcSpaces = substr_count($originalCore, ' ');
    $outSpaces = substr_count($translated, ' ');
    if ($srcSpaces > 0 && $outSpaces < $srcSpaces) {
        $pieces = [];
        foreach ($json[0] as $part) {
            if (is_array($part) && isset($part[0]) && is_string($part[0]) && $part[0] !== '') {
                $pieces[] = $part[0];
            }
        }
        if (count($pieces) > 1) {
            $translated = implode(' ', $pieces);
        }
    }

    return $translated;
}

function countUnrestoredMarkup(string $s, array $htmlPlaceholders): int
{
    $missing = 0;
    foreach (array_keys($htmlPlaceholders) as $token) {
        if (str_contains($s, $token)) {
            $missing++;
        }
    }

    return $missing;
}

function repairHeuristicSpaces(string $text, string $locale): string
{
    if (! localeUsesWordSpaces($locale)) {
        return $text;
    }

    $text = preg_replace('/([^\s>])(<(?:\/?[a-z][^>]*>))/iu', '$1 $2', $text) ?? $text;
    $text = preg_replace('/(>)([A-Za-zÀ-ỹ])/u', '$1 $2', $text) ?? $text;

    return preg_replace('/\s{2,}/u', ' ', $text) ?? $text;
}

function alignHtmlTranslationSpacing(string $source, string $translated, string $locale): string
{
    $srcParts = preg_split('/(<[^>]+>)/', $source, -1, PREG_SPLIT_DELIM_CAPTURE);
    $dstParts = preg_split('/(<[^>]+>)/', $translated, -1, PREG_SPLIT_DELIM_CAPTURE);

    if ($srcParts === false || $dstParts === false || count($srcParts) !== count($dstParts)) {
        return repairHeuristicSpaces($translated, $locale);
    }

    $out = '';
    foreach ($srcParts as $i => $src) {
        $dst = $dstParts[$i];
        if ($src === '') {
            continue;
        }
        if (str_starts_with($src, '<') && str_ends_with($src, '>')) {
            $out .= $dst;

            continue;
        }
        [$lead, , $trail] = splitOuterWhitespace($src);
        [, $core] = splitOuterWhitespace($dst);
        $out .= $lead . $core . $trail;
    }

    return repairHeuristicSpaces($out, $locale);
}

/** @return array{0: string, 1: array<string, string>, 2: array<string, string>} */
function prepareForGoogle(string $text): array
{
    [$plain, $phPlace] = protectPlaceholders($text);
    [$plain, $phHtml] = protectHtml($plain);
    $plain = padMarkupTokens($plain);

    return [$plain, $phHtml, $phPlace];
}

function finishFromGoogle(string $translated, array $phHtml, array $phPlace): string
{
    $translated = restoreHtml($translated, $phHtml);

    return restoreTokens($translated, $phPlace);
}

function callGoogleTranslate(string $plain, string $locale, string $originalForJoin, callable $googleTl): ?string
{
    $tl = $googleTl($locale);
    $sl = TRANSLATION_SOURCE_LOCALE;
    $url = 'https://translate.googleapis.com/translate_a/single?client=gtx&sl=' . rawurlencode($sl) . '&tl=' . rawurlencode($tl) . '&dt=t&q=' . rawurlencode($plain);
    $ctx = stream_context_create(['http' => ['timeout' => 45, 'header' => "User-Agent: ninedashline-dev-translate\r\n"]]);
    $raw = false;
    for ($attempt = 1; $attempt <= 3; $attempt++) {
        $raw = @file_get_contents($url, false, $ctx);
        if ($raw !== false) {
            break;
        }
        if ($attempt < 3) {
            usleep(500000 * $attempt);
        }
    }
    if ($raw === false) {
        return null;
    }

    $json = json_decode($raw, true);
    $translated = joinGoogleTranslateParts(is_array($json) ? $json : [], $originalForJoin, $locale);

    return $translated !== '' ? $translated : null;
}

function translateText(string $text, string $locale, array &$cache, callable $googleTl): string
{
    if ($text === '' || trim($text) === '') {
        return $text;
    }
    $tl = $googleTl($locale);
    $hash = md5(TRANSLATION_CACHE_VERSION . '|' . TRANSLATION_SOURCE_LOCALE . '|' . $tl . '|' . $text);
    if (isset($cache[$hash])) {
        return $cache[$hash];
    }

    [$plain, $phHtml, $phPlace] = prepareForGoogle($text);
    $translated = callGoogleTranslate($plain, $locale, $text, $googleTl);
    if ($translated === null) {
        return $text;
    }

    $translated = finishFromGoogle($translated, $phHtml, $phPlace);
    $cache[$hash] = $translated;
    usleep(100000);

    return $translated;
}

function translateSegment(string $segment, string $locale, array &$cache, callable $googleTl): string
{
    if ($segment === '') {
        return '';
    }
    [$lead, $core, $trail] = splitOuterWhitespace($segment);
    if ($core === '') {
        return $segment;
    }

    return $lead . translateText($core, $locale, $cache, $googleTl) . $trail;
}

function translateHtmlAware(string $text, string $locale, array &$cache, callable $googleTl): string
{
    if (! str_contains($text, '<')) {
        return translateSegment($text, $locale, $cache, $googleTl);
    }

    [$lead, $core, $trail] = splitOuterWhitespace($text);
    if ($core === '') {
        return $text;
    }

    [$plain, $phHtml, $phPlace] = prepareForGoogle($core);
    $translated = callGoogleTranslate($plain, $locale, $core, $googleTl);
    if ($translated === null) {
        return $text;
    }

    $restored = finishFromGoogle($translated, $phHtml, $phPlace);
    $out = $lead . $restored . $trail;

    return alignHtmlTranslationSpacing($text, $out, $locale);
}

function translateValue(string $text, string $locale, array &$cache, callable $googleTl): string
{
    if (trim($text) === '') {
        return $text;
    }
    if (str_contains($text, '<')) {
        return translateHtmlAware($text, $locale, $cache, $googleTl);
    }

    return translateSegment($text, $locale, $cache, $googleTl);
}
