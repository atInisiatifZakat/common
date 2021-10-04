<?php

declare(strict_types=1);

namespace Inisiatif\Package\Common;

final class Common
{
    private static bool $runningMigrations = true;

    public static function ignoreMigrations(): void
    {
        self::$runningMigrations = false;
    }

    public static function runningMigrations(bool $value = true): void
    {
        self::$runningMigrations = $value;
    }

    public static function isRunningMigrations(): bool
    {
        return self::$runningMigrations;
    }
}
