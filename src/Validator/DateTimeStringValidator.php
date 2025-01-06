<?php

namespace Rikudou\ActivityPub\Validator;

use DateMalformedStringException;
use DateTimeImmutable;
use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class DateTimeStringValidator implements Validator
{
    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        if (!is_string($value)) {
            return ['the value must be a valid date time string'];
        }

        try {
            new DateTimeImmutable($value);
            return [];
        } catch (DateMalformedStringException) {
            return ['the value must be a valid date time string'];
        }
    }
}
