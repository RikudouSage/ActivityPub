<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Actor;

use Rikudou\ActivityPub\Vocabulary\Core\Actor;

/**
 * Represents a formal or informal collective of Actors.
 */
class Group extends Actor
{
    public string $type {
        get => 'Group';
    }
}
