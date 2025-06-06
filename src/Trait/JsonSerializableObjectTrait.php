<?php

namespace Rikudou\ActivityPub\Trait;

use DateTimeInterface;
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
use Rikudou\ActivityPub\Exception\InvalidOperationException;
use Rikudou\ActivityPub\Exception\MissingRequiredPropertyException;
use Rikudou\ActivityPub\Vocabulary\Core\Link;
use function Rikudou\ArrayMergeRecursive\array_merge_recursive;

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

        $context = [];

        $result = [];
        foreach ($reflection->getProperties(ReflectionProperty::IS_PUBLIC ^ ReflectionProperty::IS_STATIC) as $property) {
            if ($this->getAttribute($property, IgnoreProperty::class)) {
                continue;
            }

            try {
                $value = $property->getValue($this);
            } catch (InvalidOperationException) {
                continue;
            }

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

            $value = $this->convertToSerializableValue($value, $context);

            if ($value === null) {
                continue;
            }
            if ($value instanceof NullID) {
                $value = null;
            }

            $result[$targetName] = $value;
        }

        if (property_exists($this, 'customProperties') && is_array($this->customProperties)) {
            foreach ($this->customProperties as $property => $value) {
                $result[$property] = $this->convertToSerializableValue($value, $context);
            }
        }

        if (isset($result['@context'])) {
            $context = array_merge_recursive($context, is_array($result['@context']) ? $result['@context'] : [$result['@context']]);
            $resultingContext = [];
            $associativeContext = [];

            foreach ($context as $item) {
                if (is_array($item) && array_is_list($item)) {
                    $resultingContext = array_merge_recursive($resultingContext, $item);
                } else if (is_array($item)) {
                    $associativeContext = array_merge_recursive($associativeContext, $item);
                } else if (!in_array($item, $resultingContext, true)) {
                    $resultingContext[] = $item;
                }
            }
            if ($associativeContext) {
                $resultingContext[] = $associativeContext;
            }

            $result['@context'] = $resultingContext;
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

    private function convertToSerializableValue(mixed $value, array &$context): mixed
    {
        if ($value instanceof Link && $value->simple) {
            return (string) $value;
        }
        if ($value instanceof JsonSerializable) {
            $result = $value->jsonSerialize();
            if (isset($result['@context'])) {
                $context = array_merge_recursive($context, is_array($result['@context']) ? $result['@context'] : [$result['@context']]);
                unset($result['@context']);
            }

            return $result;
        }
        if ($value instanceof OmittedID) {
            return null;
        }
        if ($value instanceof DateTimeInterface) {
            return $value->format('c');
        }

        if (is_array($value)) {
            return array_map(function ($item) use (&$context) {
                return $this->convertToSerializableValue($item, $context);
            }, $value);
        }

        return $value;
    }
}
