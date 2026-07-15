<?php

declare(strict_types=1);

namespace Saloon\Barstool\Exceptions;

use Exception;

class InvalidActionClass extends Exception
{
    public static function make(string $actionName, string $actionBaseClass, string $actionClass): self
    {
        return new self("The action `{$actionName}` must extend `{$actionBaseClass}`. The configured class `{$actionClass}` does not.");
    }
}
