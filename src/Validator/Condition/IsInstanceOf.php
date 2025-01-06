<?php

namespace Rikudou\ActivityPub\Validator\Condition;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class IsInstanceOf implements Condition
{
    /**
     * @param class-string $class
     */
    public function __construct(
        private string $class,
    ) {
    }

    public function __invoke(mixed $value, ValidatorMode $mode): bool
    {
        return is_a($value, $this->class, true);
    }
}
