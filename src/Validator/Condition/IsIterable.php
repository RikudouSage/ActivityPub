<?php

namespace Rikudou\ActivityPub\Validator\Condition;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class IsIterable implements Condition
{
    public function __invoke(mixed $value, ValidatorMode $mode): bool
    {
        return is_iterable($value);
    }
}
