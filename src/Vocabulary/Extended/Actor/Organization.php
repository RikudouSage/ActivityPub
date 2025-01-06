<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Actor;

use Rikudou\ActivityPub\Vocabulary\Core\Actor;

class Organization extends Actor
{
    public string $type {
        get => 'Organization';
    }
}
