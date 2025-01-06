<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

/**
 * A specialization of {@see Accept} indicating that the acceptance is tentative.
 */
class TentativeAccept extends Accept
{
    public string $type {
        get => 'TentativeAccept';
    }
}
