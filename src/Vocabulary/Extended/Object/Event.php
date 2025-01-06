<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;

class Event extends BaseObject
{
    public string $type {
        get => 'Event';
    }
}
