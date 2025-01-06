<?php

namespace Rikudou\ActivityPub\Validator\Condition;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class NotNull implements Condition
{
    public function __invoke(mixed $value, ValidatorMode $mode): bool
    {
        return $value !== null;
    }
}
