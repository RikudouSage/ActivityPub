<?php

namespace Rikudou\ActivityPub\Attribute;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class LangMapProperty
{
    public function __construct(
        public string $suffix = 'Map',
    ) {
    }
}
