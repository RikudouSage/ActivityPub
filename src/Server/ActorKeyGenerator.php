<?php

namespace Rikudou\ActivityPub\Server;

use Rikudou\ActivityPub\Dto\KeyPair;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;

interface ActorKeyGenerator
{
    public function generate(?ActivityPubActor $actor = null, int $bits = 4096): KeyPair;
}
