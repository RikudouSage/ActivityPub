<?php

namespace Rikudou\ActivityPub\Server\ObjectFetcher;

use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

interface ObjectFetcher
{
    public function fetch(Link|string $url): ActivityPubObject;
}
