<?php

namespace Rikudou\ActivityPub\Validator;

use Closure;
use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class AllIterableChildrenValidator implements Validator
{
    /**
     * @var Closure(mixed $value, ValidatorMode $mode): array
     */
    private Closure $validator;

    /**
     * @param callable(mixed $value, ValidatorMode $mode): array $validator
     */
    public function __construct(
        callable $validator,
    ) {
        $this->validator = $validator(...);
    }

    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        if (is_object($value)) {
            $value = (array) $value;
        }
        if (!is_iterable($value)) {
            return ['the value must be iterable'];
        }

        $errors = [];
        foreach ($value as $item) {
            $errors = [...$errors, ...($this->validator)($item, $mode)];
        }

        return $errors;
    }
}
