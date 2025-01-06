<?php

namespace Rikudou\ActivityPub\Validator;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class NumberInRangeValidator implements Validator
{
    public function __construct(
        private float $min,
        private float $max,
    ) {
    }

    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        if (!is_int($value) && !is_float($value)) {
            return ["the value must be a number in the range {$this->min} and {$this->max}"];
        }

        if ($value < $this->min || $value > $this->max) {
            return ["the value must be between {$this->min} and {$this->max}"];
        }

        return [];
    }
}
