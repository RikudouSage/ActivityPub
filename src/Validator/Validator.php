<?php

namespace Rikudou\ActivityPub\Validator;

use Rikudou\ActivityPub\Enum\ValidatorMode;

interface Validator
{
    /**
     * @return array<string>
     */
    public function __invoke(mixed $value, ValidatorMode $mode): array;
}
