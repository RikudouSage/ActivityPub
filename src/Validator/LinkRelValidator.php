<?php

namespace Rikudou\ActivityPub\Validator;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final readonly class LinkRelValidator implements Validator
{
    public function __invoke(mixed $value, ValidatorMode $mode): array
    {
        if (!is_string($value)) {
            return ['the value must be a string'];
        }

        $banned = ["\u{0020}", "\u{0009}", "\u{000A}", "\u{000C}", "\u{000D}", "\u{002C}"];
        if (array_any($banned, fn ($char) => str_contains($value, $char))) {
            return ['your link contains invalid characters'];
        }

        return [];
    }
}
