<?php

declare(strict_types=1);

namespace App\Helpers;

use Illuminate\Support\Facades\Auth;

class AdminMenuHelper
{
    public static function getMenuSections(): array
    {
        $user = Auth::user();
        if ($user === null) {
            return [];
        }

        $sections = [];
        foreach (config('menu.admin-menu-sections', []) as $sectionKey => $section) {
            if (self::userHasAnyRole($user, $section['role'] ?? [])) {
                $sections[$sectionKey] = ['title' => $section['title']];
            }
        }

        return $sections;
    }

    public static function getMenuItems(string $section): array
    {
        $config = config('menu.admin-menu-sections.' . $section, []);
        if (empty($config['items'])) {
            return [];
        }

        $user = Auth::user();
        if ($user === null) {
            return [];
        }

        $currentRoute = request()->route()?->getName() ?? '';
        $items = [];

        foreach ($config['items'] as $item) {
            if (isset($item['role']) && ! self::userHasAnyRole($user, $item['role'])) {
                continue;
            }

            if (! isset($item['route']) && ! isset($item['url'])) {
                continue;
            }

            $menuItem = [
                'label' => $item['label'],
                'svg' => $item['svg'] ?? null,
                'url' => '#',
                'route' => null,
                'active' => false,
                'onclick' => $item['onclick'] ?? null,
            ];

            if (isset($item['route'])) {
                $menuItem['route'] = $item['route'];
                $params = $item['route_params'] ?? [];
                $menuItem['url'] = route($item['route'], $params);
                $prefix = $item['active_route_prefix'] ?? null;
                $exclude = $item['active_route_exclude_prefix'] ?? null;
                if ($prefix) {
                    $menuItem['active'] = str_starts_with($currentRoute, $prefix)
                        && ($exclude === null || ! str_starts_with($currentRoute, $exclude));
                } else {
                    $menuItem['active'] = $currentRoute === $item['route']
                        || str_starts_with($currentRoute, rtrim($item['route'], '.list'));
                }
            } elseif (isset($item['url'])) {
                $menuItem['url'] = $item['url'];
            }

            $items[] = $menuItem;
        }

        return $items;
    }

    /** @param list<string> $roles */
    private static function userHasAnyRole(object $user, array $roles): bool
    {
        if ($roles === []) {
            return true;
        }

        if (! method_exists($user, 'hasRole')) {
            return false;
        }

        foreach ($roles as $role) {
            if ($user->hasRole($role)) {
                return true;
            }
        }

        return false;
    }
}
