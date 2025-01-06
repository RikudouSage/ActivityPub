<?php

namespace Rikudou\ActivityPub\Validator;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class PropertyValueIsStringValidator implements Validator
{
    public function __construct(
        private string $propertyName,
    ) {
    }

    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        if (!is_object($value) || !property_exists($value, $this->propertyName)) {
            return ["the value must be an object which has a string {$this->propertyName} property"];
        }

        if (!is_string($value->{$propertyName})) {
            return ["the type of the {$this->propertyName} property must be a string"];
        }

        return [];
    }
}
