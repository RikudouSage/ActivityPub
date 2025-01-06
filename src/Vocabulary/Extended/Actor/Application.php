<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Actor;

use Rikudou\ActivityPub\Vocabulary\Core\Actor;

/**
 * Describes a software application.
 */
class Application extends Actor
{
    public string $type {
        get => 'Application';
    }
}
