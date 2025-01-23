<?php

namespace Rikudou\ActivityPub\Dto;

use Rikudou\ActivityPub\Server\Abstraction\LocalActor;

final readonly class ActorKey implements LocalActor
{
    public function __construct(
        private ?string $privateKey,
        private ?string $keyId,
    ) {
    }

    public function getPrivateKey(): ?string
    {
        return $this->privateKey;
    }

    public function getKeyId(): ?string
    {
        return $this->keyId;
    }
}
