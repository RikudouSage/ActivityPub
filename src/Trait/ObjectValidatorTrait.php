<?php

namespace Rikudou\ActivityPub\Trait;

use JsonSerializable;
use Rikudou\ActivityPub\Attribute\IgnoreProperty;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Exception\InvalidPropertyValueException;
use Rikudou\ActivityPub\GlobalSettings;
use Stringable;

trait ObjectValidatorTrait
{
    #[IgnoreProperty]
    public ValidatorMode $validatorMode {
        get {
            $this->validatorMode ??= GlobalSettings::$validatorMode;
            return $this->validatorMode;
        }
        set => $this->validatorMode = $value;
    }

    /**
     * @return iterable<string, callable(mixed $value, ValidatorMode $mode): array<string>>
     */
    protected function getValidators(): iterable
    {
        return [];
    }

    protected function validate(string $propertyName, mixed $value): void
    {
        if ($this->validatorMode === ValidatorMode::None) {
            return;
        }

        $validators = $this->getValidators();
        if (!is_array($validators)) {
            $validators = iterator_to_array($validators);
        }

        if (isset($validators[$propertyName])) {
            $validator = $validators[$propertyName];
            if ($errors = $validator($value, $this->validatorMode)) {
                $representation = get_debug_type($value);
                if (is_scalar($value) || $value instanceof Stringable) {
                    $representation .= '(' . $value . ')';
                } else if (is_array($value) || $value instanceof JsonSerializable) {
                    $representation .= '(' . json_encode($value, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . ')';
                }

                $errorMessage = implode(', ', $errors);

                $baseMessage = "The value for property '{$propertyName}' is not valid";
                if ($this->validatorMode === ValidatorMode::Recommended) {
                    $baseMessage .= ' (note that you are using the Recommended mode which is an opinionated validation mode which is in some cases stricter than the specification mandates)';
                }

                throw new InvalidPropertyValueException("{$baseMessage}: {$representation}: {$errorMessage}");
            }
        }
    }

    protected function validateEntity(): void
    {
    }
}
