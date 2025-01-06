<?php

namespace Rikudou\ActivityPub\Validator;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class CompoundValidator implements Validator
{
    /**
     * @var array<callable(mixed $value, ValidatorMode $mode): array<string>>
     */
    private array $validators;

    public function __construct(callable ...$validators)
    {
        $this->validators = $validators;
    }

    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        $errors = [];
        foreach ($this->validators as $validator) {
            $errors = [...$errors, ...$validator($value, $mode)];
        }

        return $errors;
    }
}
