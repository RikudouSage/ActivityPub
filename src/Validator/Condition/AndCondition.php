<?php

namespace Rikudou\ActivityPub\Validator\Condition;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class AndCondition implements Condition
{
    /**
     * @var array<callable(mixed $value, ValidatorMode $mode): bool>
     */
    private array $conditions;

    /**
     * @param callable(mixed $value, ValidatorMode $mode): bool ...$conditions
     */
    public function __construct(
        callable ...$conditions,
    ) {
        $this->conditions = $conditions;
    }

    public function __invoke(mixed $value, ValidatorMode $mode): bool
    {
        return array_all(
            $this->conditions,
            fn (callable $condition) => $condition($value, $mode),
        );
    }
}
