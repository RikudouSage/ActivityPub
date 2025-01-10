<?php

namespace Rikudou\ActivityPub\Validator;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class IsArrayValidator implements Validator
{
    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        if (!is_array($value)) {
            return ['the value must be an array.'];
        }

        return [];
    }
}
