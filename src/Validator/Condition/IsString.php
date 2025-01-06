<?php

namespace Rikudou\ActivityPub\Validator\Condition;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class IsString implements Condition
{
    public function __invoke(mixed $value, ValidatorMode $mode): bool
    {
        return is_string($value);
    }
}
