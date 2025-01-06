<?php

namespace Rikudou\ActivityPub\Trait;

use JsonSerializable;
use ReflectionObject;
use ReflectionProperty;
use Rikudou\ActivityPub\Attribute\IgnoreProperty;
use Rikudou\ActivityPub\Attribute\LangMapProperty;
use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Attribute\SerializedName;
use Rikudou\ActivityPub\Dto\NullID;
use Rikudou\ActivityPub\Dto\OmittedID;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Exception\MissingRequiredPropertyException;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

trait JsonSerializableObjectTrait
{
    public function jsonSerialize(): array
    {
        $currentMode = property_exists($this, 'validatorMode') ? $this->validatorMode : ValidatorMode::Strict;
        assert($currentMode instanceof ValidatorMode);

        if (method_exists($this, 'validateEntity')) {
            $this->validateEntity();
        }

        $reflection = new ReflectionObject($this);

        $result = [];
        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC ^ ReflectionProperty::IS_STATIC) as $property) {
            if ($this->getAttribute($property, IgnoreProperty::class)) {
                continue;
            }

            $value = $property->getValue($this);
            $required = $this->getAttribute($property, RequiredProperty::class);
            if ($required && $required->validatorMode->value <= $currentMode->value && $value === null) {
                throw new MissingRequiredPropertyException(sprintf(
                    'The property "%s:%s" is required when running in "%s" validator mode.',
                    $this::class,
                    $property->getName(),
                    $currentMode->name,
                ));
            }

            $targetName = $this->getAttribute($property, SerializedName::class)?->name ?? $property->getName();
            $langMapProperty = $this->getAttribute($property, LangMapProperty::class);
            if ($langMapProperty) {
                $this->handleLangMapProperty($targetName, $value, $langMapProperty->suffix);
            }

            if ($value instanceof Link && $value->simple) {
                $value = (string) $value;
            }
            if ($value instanceof JsonSerializable) {
                $value = $value->jsonSerialize();
            }

            if ($value instanceof OmittedID) {
                $value = null;
            }
            if ($value === null) {
                continue;
            }
            if ($value instanceof NullID) {
                $value = null;
            }

            $result[$targetName] = $value;
        }

        return $result;
    }

    protected function handleLangMapProperty(string &$name, string|array|null $value, string $suffix): void
    {
        if (!is_array($value)) {
            return;
        }

        $name .= $suffix;
    }

    /**
     * @template T of object
     * @param class-string<T> $attributeClass
     * @return T|null
     */
    protected function getAttribute(ReflectionProperty $property, string $attributeClass): ?object
    {
        $attributes = $property->getAttributes($attributeClass);
        if (!count($attributes)) {
            return null;
        }

        return $attributes[array_key_first($attributes)]->newInstance();
    }
}
