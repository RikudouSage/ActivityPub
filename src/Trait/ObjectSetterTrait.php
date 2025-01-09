<?php

namespace Rikudou\ActivityPub\Trait;

use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Exception\InvalidValueException;

trait ObjectSetterTrait
{
    use ObjectValidatorTrait;

    /**
     * @var array<string, mixed>
     */
    protected array $customProperties = [];

    protected bool $__directSet = false;

    public function set(string $propertyName, mixed $value): void
    {
        if (!property_exists($this, $propertyName)) {
            if ($this->validatorMode !== ValidatorMode::None) {
                throw new InvalidValueException("Cannot set a custom property '{$propertyName}' when validation is enabled. Either disable validation for this object, or use the runInNoValidationContext() function");
            }
            $this->customProperties[$propertyName] = $value;
        } else {
            $this->validate($propertyName, $value);
            $this->__directSet = true;
            $this->{$propertyName} = $value;
            $this->__directSet = false;
        }
    }

    public function get(string $propertyName): mixed
    {
        if (property_exists($this, $propertyName)) {
            return $this->{$propertyName};
        }

        if (array_key_exists($propertyName, $this->customProperties)) {
            return $this->customProperties[$propertyName];
        }

        return null;
    }
}
