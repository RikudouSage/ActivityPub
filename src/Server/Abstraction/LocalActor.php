<?php

namespace Rikudou\ActivityPub\Server\Abstraction;

interface LocalActor
{
    public function getPrivateKey(): ?string;
    public function getKeyId(): ?string;
}
