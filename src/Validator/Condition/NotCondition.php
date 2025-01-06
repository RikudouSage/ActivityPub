<?php

namespace Rikudou\ActivityPub\Validator\Condition;

use Closure;
use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class NotCondition implements Condition
{
    /**
     * @var Closure(mixed $value, ValidatorMode $mode): bool
     */
    private Closure $condition;

    /**
     * @param callable(mixed $value, ValidatorMode $mode): bool $condition
     */
    public function __construct(
        callable $condition,
    ) {
        $this->condition = $condition(...);
    }

    public function __invoke(mixed $value, ValidatorMode $mode): bool
    {
        return !($this->condition)($value, $mode);
    }
}
