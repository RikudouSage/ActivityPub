<?php

namespace Rikudou\ActivityPub\Vocabulary\Contract;

use Rikudou\ActivityPub\Exception\InvalidOperationException;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

interface ActivityPubIntransitiveActivity extends ActivityPubActivity
{
    /**
     * @internal
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    public ActivityPubObject|Link|array|null $object {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }
}
