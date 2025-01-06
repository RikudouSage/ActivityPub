<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

class TentativeAccept extends Accept
{
    public string $type {
        get => 'TentativeAccept';
    }
}
