<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Actor;

use Rikudou\ActivityPub\Vocabulary\Core\Actor;

/**
 * Represents an individual person.
 */
class Person extends Actor
{
    public string $type {
        get => 'Person';
    }
}
