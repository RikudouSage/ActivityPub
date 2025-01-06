<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

/**
 * A specialization of {@see Reject} in which the rejection is considered tentative.
 */
class TentativeReject extends Reject
{
    public string $type {
        get => 'TentativeReject';
    }
}
