<?php

namespace Rikudou\ActivityPub\Validator;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class CountValidator implements Validator
{
    public function __construct(
        private int $count,
    ) {
    }

    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        if (!is_countable($value)) {
            return ["the value must be a countable item with the count of {$this->count}"];
        }

        if (count($value) !== $this->count) {
            return ["the value must be a countable item with the count of {$this->count}"];
        }

        return [];
    }
}
