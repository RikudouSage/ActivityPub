<?php

namespace Rikudou\ActivityPub\Validator;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final class IsStringValidator implements Validator
{
    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        if (!is_string($value)) {
            return ['the value must be a string'];
        }

        return [];
    }
}
