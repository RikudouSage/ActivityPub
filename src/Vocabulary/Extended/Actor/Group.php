<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Actor;

use Rikudou\ActivityPub\Vocabulary\Core\Actor;

class Group extends Actor
{
    public string $type {
        get => 'Group';
    }
}
