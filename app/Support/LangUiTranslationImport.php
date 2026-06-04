<?php

declare(strict_types=1);

namespace App\Support;

use InvalidArgumentException;

final class LangUiTranslationImport
{
    /** @var array<string, true> */
    private static array $allowedKeyLookup = [];

    /**
     * @param list<string> $allowedKeys
     * @return array<string, string>
     */
    public static function parse(string $raw, array $allowedKeys): array
    {
        $raw = trim($raw);
        if ($raw === '') {
            throw new InvalidArgumentException('Nội dung nhập trống.');
        }

        self::$allowedKeyLookup = array_fill_keys($allowedKeys, true);
        try {
            $raw = self::stripCodeFences($raw);
            $decoded = json_decode($raw, true);
            if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                return self::normalizeMap($decoded, $allowedKeys);
            }

            $map = self::parseRelaxed($raw);
            if ($map !== []) {
                return self::normalizeMap($map, $allowedKeys);
            }
        } finally {
            self::$allowedKeyLookup = [];
        }

        throw new InvalidArgumentException('Không đọc được JSON/array. Dán object JSON {"key":"value",...} hoặc PHP array.');
    }

    /**
     * Nhập từ object/array đã decode (JSON.parse phía trình duyệt) — tránh mất escape \\ khi parse lại chuỗi.
     *
     * @param array<mixed> $decoded
     * @param list<string> $allowedKeys
     * @return array<string, string>
     */
    public static function fromDecoded(array $decoded, array $allowedKeys): array
    {
        return self::normalizeMap($decoded, $allowedKeys);
    }

    /**
     * @param array<string, string> $map
     * @return list<string>
     */
    public static function warnings(array $map): array
    {
        $out = [];

        if (isset($map['footer_legal'])) {
            $legal = $map['footer_legal'];
            foreach ([':hoangsa', ':truongsa', ':paracel', ':spratly'] as $placeholder) {
                if (! str_contains($legal, $placeholder)) {
                    $out[] = 'footer_legal thiếu ' . $placeholder
                        . ' — có thể bị cắt do dấu " trong JSON (href="...").';
                }
            }
            if (substr_count($legal, '<a ') !== substr_count($legal, '</a>')) {
                $out[] = 'footer_legal có thẻ <a> chưa đóng — HTML sẽ vỡ, footer có thể không hiển thị.';
            }
        }

        return $out;
    }

    /**
     * @return array<string, string>
     */
    private static function parseRelaxed(string $raw): array
    {
        $raw = trim($raw);
        $raw = preg_replace('/^<\?php\s*/iu', '', $raw) ?? $raw;
        $raw = preg_replace('/^\s*return\s+/iu', '', $raw) ?? $raw;
        $raw = rtrim($raw, ";\r\n\t ");

        if ($raw === '') {
            return [];
        }

        $first = $raw[0];
        if ($first === '[') {
            $inner = trim(substr($raw, 1, -1));
            $fromList = self::parseRelaxedList($inner);
            if ($fromList !== []) {
                return $fromList;
            }

            return self::parseRelaxedObject($inner);
        }

        if ($first === '{') {
            return self::parseRelaxedObject(trim(substr($raw, 1, -1)));
        }

        return self::parseRelaxedObject($raw);
    }

    /**
     * @return array<string, string>
     */
    private static function parseRelaxedList(string $body): array
    {
        $map = [];
        foreach (self::splitTopLevel($body, '{', '}') as $chunk) {
            $row = self::parseRelaxedObject(trim($chunk));
            if (isset($row['key'], $row['value']) && is_string($row['key']) && is_string($row['value'])) {
                $map[$row['key']] = $row['value'];
                continue;
            }
            foreach ($row as $key => $value) {
                if (is_string($key) && is_string($value)) {
                    $map[$key] = $value;
                }
            }
        }

        return $map;
    }

    /**
     * @return array<string, string>
     */
    private static function parseRelaxedObject(string $body): array
    {
        $map = [];
        $pos = 0;
        $len = strlen($body);

        while ($pos < $len) {
            $pos = self::skipWhitespace($body, $pos, $len);
            if ($pos >= $len) {
                break;
            }

            if ($body[$pos] === ',') {
                $pos++;
                continue;
            }

            $key = self::readKey($body, $pos, $len);
            if ($key === null) {
                break;
            }

            $pos = self::skipWhitespace($body, $pos, $len);
            if ($pos + 1 < $len && $body[$pos] === '=' && $body[$pos + 1] === '>') {
                $pos += 2;
            } elseif ($pos < $len && $body[$pos] === ':') {
                $pos++;
            } else {
                break;
            }

            $value = self::readValue($body, $pos, $len);
            if ($value === null) {
                break;
            }

            $map[$key] = $value;
        }

        return $map;
    }

    /**
     * @return list<string>
     */
    private static function splitTopLevel(string $body, string $open, string $close): array
    {
        $items = [];
        $depth = 0;
        $start = 0;
        $len = strlen($body);
        $inString = false;
        $stringQuote = '';

        for ($i = 0; $i < $len; $i++) {
            $ch = $body[$i];

            if ($inString) {
                if ($ch === '\\') {
                    $i++;
                    continue;
                }
                if ($ch === $stringQuote) {
                    if (! self::isLikelyStringTerminator($body, $i + 1, $stringQuote)) {
                        continue;
                    }
                    $inString = false;
                    $stringQuote = '';
                }
                continue;
            }

            if ($ch === '"' || $ch === "'") {
                $inString = true;
                $stringQuote = $ch;
                continue;
            }

            if ($ch === $open) {
                if ($depth === 0) {
                    $start = $i;
                }
                $depth++;
                continue;
            }

            if ($ch === $close && $depth > 0) {
                $depth--;
                if ($depth === 0) {
                    $items[] = substr($body, $start + 1, $i - $start - 1);
                }
            }
        }

        return $items;
    }

    private static function readKey(string $body, int &$pos, int $len): ?string
    {
        $pos = self::skipWhitespace($body, $pos, $len);
        if ($pos >= $len) {
            return null;
        }

        $quote = $body[$pos];
        if ($quote === '"' || $quote === "'") {
            return self::readQuotedString($body, $pos, $len, $quote, false);
        }

        if (! preg_match('/[a-zA-Z_]/', $body[$pos])) {
            return null;
        }

        $start = $pos;
        while ($pos < $len && preg_match('/[a-zA-Z0-9_]/', $body[$pos])) {
            $pos++;
        }

        return substr($body, $start, $pos - $start);
    }

    private static function readValue(string $body, int &$pos, int $len): ?string
    {
        $pos = self::skipWhitespace($body, $pos, $len);
        if ($pos >= $len) {
            return null;
        }

        $quote = $body[$pos];
        if ($quote === '"' || $quote === "'") {
            return self::readQuotedString($body, $pos, $len, $quote, true);
        }

        $start = $pos;
        while ($pos < $len && ! in_array($body[$pos], [',', '}', ']'], true)) {
            $pos++;
        }

        return trim(substr($body, $start, $pos - $start));
    }

    private static function readQuotedString(
        string $body,
        int &$pos,
        int $len,
        string $quote,
        bool $useTerminatorHeuristic,
    ): string {
        $pos++;
        $value = '';

        while ($pos < $len) {
            $ch = $body[$pos];

            if ($ch === '\\' && $pos + 1 < $len) {
                $next = $body[$pos + 1];
                if ($quote === "'") {
                    if ($next === "'" || $next === '\\') {
                        $value .= $next;
                        $pos += 2;
                        continue;
                    }
                    $value .= '\\' . $next;
                    $pos += 2;
                    continue;
                }
                if ($quote === '"') {
                    $value .= self::translateJsonEscape($next);
                    $pos += 2;
                    continue;
                }
            }

            if ($quote === "'" && $ch === "'" && $pos + 1 < $len && $body[$pos + 1] === "'") {
                $value .= "'";
                $pos += 2;
                continue;
            }

            if ($ch === $quote) {
                if ($useTerminatorHeuristic && ! self::isLikelyStringTerminator($body, $pos + 1, $quote)) {
                    $value .= $quote;
                    $pos++;
                    continue;
                }

                $pos++;
                break;
            }

            $value .= $ch;
            $pos++;
        }

        return $value;
    }

    private static function isLikelyStringTerminator(string $body, int $pos, string $quote): bool
    {
        $pos = self::skipWhitespace($body, $pos, strlen($body));
        if ($pos >= strlen($body)) {
            return true;
        }

        $ch = $body[$pos];
        if (in_array($ch, ['}', ']', ','], true)) {
            if ($ch !== ',') {
                return true;
            }

            $nextPos = self::skipWhitespace($body, $pos + 1, strlen($body));
            if ($nextPos >= strlen($body)) {
                return true;
            }

            $saved = $nextPos;
            $nextKey = self::readKey($body, $saved, strlen($body));
            if ($nextKey !== null && (isset(self::$allowedKeyLookup[$nextKey]) || self::looksLikeFieldKey($nextKey))) {
                return true;
            }

            return false;
        }

        if ($quote === "'" && $ch === '=' && ($pos + 1) < strlen($body) && $body[$pos + 1] === '>') {
            return false;
        }

        return false;
    }

    private static function looksLikeFieldKey(string $key): bool
    {
        return preg_match('/^(?:[a-z][a-z0-9_]*|cs_[a-z0-9_]+|tl_[a-z0-9_]+)$/i', $key) === 1;
    }

    private static function translateJsonEscape(string $char): string
    {
        return match ($char) {
            '"', "'", '\\', '/' => $char,
            'n' => "\n",
            'r' => "\r",
            't' => "\t",
            default => $char,
        };
    }

    private static function skipWhitespace(string $body, int $pos, int $len): int
    {
        while ($pos < $len && ctype_space($body[$pos])) {
            $pos++;
        }

        return $pos;
    }

    /**
     * @param array<mixed> $decoded
     * @param list<string> $allowedKeys
     * @return array<string, string>
     */
    private static function normalizeMap(array $decoded, array $allowedKeys): array
    {
        $allowed = array_flip($allowedKeys);
        $out = [];

        if (array_is_list($decoded)) {
            foreach ($decoded as $row) {
                if (! is_array($row)) {
                    continue;
                }
                $key = (string) ($row['key'] ?? $row['name'] ?? '');
                $val = $row['value'] ?? $row['translated'] ?? $row['text'] ?? null;
                if ($key !== '' && is_string($val) && isset($allowed[$key])) {
                    $out[$key] = $val;
                }
            }
        } else {
            foreach ($decoded as $key => $val) {
                $key = (string) $key;
                if (! isset($allowed[$key]) || ! is_string($val)) {
                    continue;
                }
                $out[$key] = $val;
            }
        }

        if ($out === []) {
            throw new InvalidArgumentException('Không khớp key nào trong section. Kiểm tra tên key.');
        }

        return $out;
    }

    private static function stripCodeFences(string $raw): string
    {
        if (preg_match('/^```(?:json|php)?\s*\n?(.*)\n?```$/uis', $raw, $m) === 1) {
            return trim($m[1]);
        }

        return $raw;
    }
}
