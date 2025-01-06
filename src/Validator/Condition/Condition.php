<?php

namespace Rikudou\ActivityPub\Validator\Condition;

use Rikudou\ActivityPub\Enum\ValidatorMode;

interface Condition
{
    public function __invoke(mixed $value, ValidatorMode $mode): bool;
}
