<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;

/**
 * Represents any kind of event.
 */
class Event extends BaseObject
{
    public string $type {
        get => 'Event';
    }
}
