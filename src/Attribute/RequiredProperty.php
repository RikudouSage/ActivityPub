<?php

namespace Rikudou\ActivityPub\Attribute;

use Attribute;
use Rikudou\ActivityPub\Enum\ValidatorMode;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class RequiredProperty
{
    public function __construct(
        public ValidatorMode $validatorMode,
    ) {
    }
}
