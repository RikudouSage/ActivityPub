<?php

namespace Rikudou\ActivityPub\Validator;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class OrValidator implements Validator
{
    /**
     * @var array<callable(mixed $value, ValidatorMode $mode): array<string>>
     */
    private array $validators;

    /**
     * @param callable(mixed $value, ValidatorMode $mode): array<string> ...$validators
     */
    public function __construct(
        callable ...$validators,
    ) {
        $this->validators = $validators;
    }

    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        $errors = [];

        foreach ($this->validators as $validator) {
            if (($result = $validator($value, $mode)) === []) {
                return [];
            }
            $errors = [...$errors, ...$result];
        }

        return $errors;
    }
}
