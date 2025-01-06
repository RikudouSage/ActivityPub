<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Actor;

use Rikudou\ActivityPub\Vocabulary\Core\Actor;

class Service extends Actor
{
    public string $type {
        get => 'Service';
    }
}
