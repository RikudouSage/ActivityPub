<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Actor;

use Rikudou\ActivityPub\Vocabulary\Core\Actor;

class Person extends Actor
{
    public string $type {
        get => 'Person';
    }
}
