<?php

namespace Rikudou\ActivityPub\Validator;

use Closure;
use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class ConditionalValidator implements Validator
{
    /**
     * @var Closure(mixed $value, ValidatorMode $mode): bool
     */
    private Closure $condition;

    /**
     * @var Closure(mixed $value, ValidatorMode $mode): bool
     */
    private Closure $validator;

    /**
     * @param callable(mixed $value, ValidatorMode $mode): bool $condition
     * @param callable(mixed $value, ValidatorMode $mode): bool $validator
     */
    public function __construct(
        callable $condition,
        callable $validator,
    ) {
        $this->condition = $condition(...);
        $this->validator = $validator(...);
    }

    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        if (($this->condition)($value, $mode)) {
            return ($this->validator)($value, $mode);
        }

        return [];
    }
}
