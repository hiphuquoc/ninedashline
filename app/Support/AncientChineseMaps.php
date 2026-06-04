<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Bản đồ cổ Trung Quốc (config/ancient_chinese_maps.php + lang_ui witnesses).
 */
final class AncientChineseMaps
{
    /**
     * @return list<array{
     *   id: string,
     *   year: int,
     *   year_label: string,
     *   title: string,
     *   body: string,
     *   image: string|null,
     *   image_alt: string
     * }>
     */
    public static function resolved(): array
    {
        /** @var list<array{id: string, year: int, year_label?: string, image?: string|null}> $items */
        $items = config('ancient_chinese_maps.items', []);

        $out = [];
        foreach ($items as $row) {
            $id = (string) $row['id'];
            $out[] = [
                'id' => $id,
                'year' => (int) $row['year'],
                'year_label' => self::yearLabelFor($id, $row),
                'title' => t("ancient_map_{$id}_title"),
                'body' => t("ancient_map_{$id}_body"),
                'image' => ! empty($row['image']) ? (string) $row['image'] : null,
                'image_alt' => t("ancient_map_{$id}_title"),
            ];
        }

        usort($out, static fn (array $a, array $b): int => $a['year'] <=> $b['year']);

        return $out;
    }

    /**
     * @param  array{id: string, year: int, year_label?: string}  $row
     */
    private static function yearLabelFor(string $id, array $row): string
    {
        $key = 'ancient_map_' . $id . '_year';
        $translated = t($key);
        if ($translated !== $key && trim($translated) !== '') {
            return $translated;
        }

        return (string) ($row['year_label'] ?? (string) ($row['year'] ?? ''));
    }
}
