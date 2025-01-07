<?php

namespace Rikudou\ActivityPub\Validator;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class IsNullValidator implements Validator
{
    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        if ($value !== null) {
            return ['the value must be a null'];
        }

        return [];
    }
}
