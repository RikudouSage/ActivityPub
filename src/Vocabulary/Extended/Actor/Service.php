<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Actor;

use Rikudou\ActivityPub\Vocabulary\Core\Actor;

/**
 * Represents a service of any kind.
 */
class Service extends Actor
{
    public string $type {
        get => 'Service';
    }
}
