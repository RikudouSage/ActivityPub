<?php

namespace Rikudou\ActivityPub\Trait;

trait ObjectSetterTrait
{
    use ObjectValidatorTrait;

    protected bool $__directSet = false;

    protected function set(string $propertyName, mixed $value): void
    {
        $this->validate($propertyName, $value);
        $this->__directSet = true;
        $this->{$propertyName} = $value;
        $this->__directSet = false;
    }
}
