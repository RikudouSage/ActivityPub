<?php

namespace Rikudou\ActivityPub\Dto;

use JsonSerializable;

final readonly class PublicKey implements JsonSerializable
{
    public function __construct(
        public string $id,
        public string $owner,
        public string $publicKeyPem,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'id' => $this->id,
            'owner' => $this->owner,
            'publicKeyPem' => $this->publicKeyPem,
        ];
    }
}
