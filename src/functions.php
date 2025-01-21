<?php

namespace Rikudou\ActivityPub;

use Rikudou\ActivityPub\Enum\ValidatorMode;

/**
 * @param callable(): void $callable
 */
function runInNoValidationContext(callable $callable): void
{
    runInCustomValidationContext(ValidatorMode::None, $callable);
}

function runInCustomValidationContext(ValidatorMode $mode, callable $callable): void
{
    $originalMode = GlobalSettings::$validatorMode;
    try {
        GlobalSettings::$validatorMode = $mode;
        $callable();
    } finally {
        GlobalSettings::$validatorMode = $originalMode;
    }
}
