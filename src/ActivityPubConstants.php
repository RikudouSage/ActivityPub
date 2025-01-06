<?php

namespace Rikudou\ActivityPub;

final readonly class ActivityPubConstants
{
    public const string DEFAULT_NAMESPACE = 'https://www.w3.org/ns/activitystreams';
    public const string PUBLIC_AUDIENCE = 'https://www.w3.org/ns/activitystreams#Public';

    private function __construct()
    {
    }
}
