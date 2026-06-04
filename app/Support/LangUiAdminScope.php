<?php

declare(strict_types=1);

namespace App\Support;

final class LangUiAdminScope
{
    public const LANDING = 'landing';

    public const CONTRIBUTE = 'contribute';

    /** @return list<string> Bundle stem (không .php) */
    public static function bundleStems(string $scope): array
    {
        return match ($scope) {
            self::CONTRIBUTE => ['chung_suc'],
            default => LandingLangBundles::stems(),
        };
    }

    public static function isValid(string $scope): bool
    {
        return in_array($scope, [self::LANDING, self::CONTRIBUTE], true);
    }
}
