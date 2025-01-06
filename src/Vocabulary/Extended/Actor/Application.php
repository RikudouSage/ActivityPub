<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Actor;

use Rikudou\ActivityPub\Vocabulary\Core\Actor;

class Application extends Actor
{
    public string $type {
        get => 'Application';
    }
}
