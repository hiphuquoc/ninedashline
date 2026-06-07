<?php

declare(strict_types=1);

namespace App\Support;

/**
 * Bản đồ cổ Trung Quốc (config/ancient_chinese_maps.php + lang_ui ancient_maps).
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
     *   image_alt: string,
     *   images: list<array{src: string, alt: string, caption: string}>
     * }>
     */
    public static function resolved(): array
    {
        /** @var list<array{id: string, year: int, year_label?: string, image?: string|null, images?: list<string>}> $items */
        $items = config('ancient_chinese_maps.items', []);

        $out = [];
        foreach ($items as $row) {
            $id = (string) $row['id'];
            $title = t("ancient_map_{$id}_title");
            $images = self::resolveImages($id, $title, $row);

            $out[] = [
                'id' => $id,
                'year' => (int) $row['year'],
                'year_label' => self::yearLabelFor($id, $row),
                'title' => $title,
                'body' => t("ancient_map_{$id}_body"),
                'image' => $images[0]['src'] ?? null,
                'image_alt' => $images[0]['alt'] ?? $title,
                'images' => $images,
            ];
        }

        usort($out, static fn (array $a, array $b): int => $a['year'] <=> $b['year']);

        return $out;
    }

    /**
     * @param  array{id: string, image?: string|null, images?: list<string>}  $row
     * @return list<array{src: string, alt: string, caption: string}>
     */
    private static function resolveImages(string $id, string $title, array $row): array
    {
        $sources = [];
        if (! empty($row['images']) && is_array($row['images'])) {
            foreach ($row['images'] as $src) {
                if (is_string($src) && trim($src) !== '') {
                    $sources[] = trim($src);
                }
            }
        }
        if ($sources === [] && ! empty($row['image'])) {
            $sources[] = (string) $row['image'];
        }

        $out = [];
        foreach ($sources as $i => $src) {
            $index = $i + 1;
            $captionKey = "ancient_map_{$id}_img{$index}_caption";
            $caption = t($captionKey);
            if ($caption === $captionKey) {
                $caption = '';
            }
            $alt = trim($caption) !== '' ? $caption : $title;

            $out[] = [
                'src' => $src,
                'alt' => $alt,
                'caption' => trim($caption),
            ];
        }

        return $out;
    }

    /**
     * Niên đại hiển thị timeline — config/lang_ui/{locale}/ancient_maps.php (ancient_map_{id}_year).
     *
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
