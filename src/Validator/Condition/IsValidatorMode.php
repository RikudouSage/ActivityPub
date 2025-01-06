<?php

namespace Rikudou\ActivityPub\Validator\Condition;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class IsValidatorMode implements Condition
{
    public function __construct(
        private ValidatorMode $wanted,
    ) {
    }

    public function __invoke(mixed $value, ValidatorMode $mode): bool
    {
        return $mode === $this->wanted;
    }
}
