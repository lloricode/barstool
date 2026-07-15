<?php

declare(strict_types=1);

namespace Saloon\Barstool\Support;

use Saloon\Barstool\Exceptions\InvalidActionClass;

class Config
{
    public static function getAction(string $actionName, string $actionBaseClass): mixed
    {
        $actionClass = self::getActionClass($actionName, $actionBaseClass);

        return app($actionClass);
    }

    public static function getActionClass(string $actionName, string $actionBaseClass): string
    {
        $actionClass = config("barstool.actions.{$actionName}");

        if ($actionClass === null) {
            return $actionBaseClass;
        }

        self::ensureValidActionClass($actionName, $actionBaseClass, $actionClass);

        return $actionClass;
    }

    protected static function ensureValidActionClass(string $actionName, string $actionBaseClass, string $actionClass): void
    {
        if (! is_a($actionClass, $actionBaseClass, true)) {
            throw InvalidActionClass::make($actionName, $actionBaseClass, $actionClass);
        }
    }
}
