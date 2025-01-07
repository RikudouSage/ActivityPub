<?php

namespace Rikudou\ActivityPub\Dto;

use SensitiveParameter;

final readonly class KeyPair
{
    public function __construct(
        #[SensitiveParameter]
        public string $privateKey,
        public string $publicKey,
    ) {
    }
}
