<?php

namespace Rikudou\ActivityPub\Validator;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class UriValidator implements Validator
{
    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        if (!is_string($value)) {
            return ['the value must be a string that contains a valid uri'];
        }

        if (!str_starts_with($value, 'http://') && !str_starts_with($value, 'https://')) {
            return ['the value must be a valid uri'];
        }

        return [];
    }
}
