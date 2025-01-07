<?php

namespace Rikudou\ActivityPub;

use Rikudou\ActivityPub\Enum\ValidatorMode;

/**
 * @param callable(): void $callable
 */
function runInNoValidationContext(callable $callable): void
{
    $originalMode = GlobalSettings::$validatorMode;
    try {
        GlobalSettings::$validatorMode = ValidatorMode::None;
        $callable();
    } finally {
        GlobalSettings::$validatorMode = $originalMode;
    }
}
