<?php

namespace Rikudou\ActivityPub\Server\CollectionResolver;

use Rikudou\ActivityPub\Server\Abstraction\LocalActor;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubCollection;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

interface CollectionResolver
{
    /**
     * @return iterable<ActivityPubObject|Link>
     */
    public function resolve(ActivityPubCollection $collection, ?LocalActor $actor = null): iterable;
}
