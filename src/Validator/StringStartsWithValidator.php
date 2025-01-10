<?php

namespace Rikudou\ActivityPub\Validator;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class StringStartsWithValidator implements Validator
{
    public function __construct(
        private string $startsWith,
    ) {
    }

    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        if (!is_string($value)) {
            return ["the value must be a string that starts with '{$this->startsWith}'"];
        }

        if (!str_starts_with($value, $this->startsWith)) {
            return ["the value must start with '{$this->startsWith}'"];
        }

        return [];
    }
}
