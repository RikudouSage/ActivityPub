<?php

namespace Rikudou\ActivityPub\Validator;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class GlobMatchValidator implements Validator
{
    public function __construct(
        private string $pattern,
    ) {
    }

    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        if (!is_string($value)) {
            return ['the value must be a string'];
        }

        if (!fnmatch($this->pattern, $value)) {
            return ["the value must match the glob pattern '{$this->pattern}'"];
        }

        return [];
    }
}
