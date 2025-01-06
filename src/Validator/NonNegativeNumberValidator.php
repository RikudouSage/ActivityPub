<?php

namespace Rikudou\ActivityPub\Validator;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class NonNegativeNumberValidator implements Validator
{
    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        if (!is_int($value) && !is_float($value)) {
            return ['the value must be a number'];
        }

        if ($value < 0) {
            return ['the value must be greater or equal to 0'];
        }

        return [];
    }
}
