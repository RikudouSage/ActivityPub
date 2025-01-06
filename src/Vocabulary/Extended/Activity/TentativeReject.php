<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

class TentativeReject extends Reject
{
    public string $type {
        get => 'TentativeReject';
    }
}
