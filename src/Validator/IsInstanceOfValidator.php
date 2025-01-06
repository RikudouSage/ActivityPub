<?php

namespace Rikudou\ActivityPub\Validator;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class IsInstanceOfValidator implements Validator
{
    /**
     * @param class-string $class
     */
    public function __construct(
        private string $class,
    ) {
    }

    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        if (!is_a($value, $this->class, true)) {
            return ['the value must be an instance of ' . $this->class];
        }

        return [];
    }
}
