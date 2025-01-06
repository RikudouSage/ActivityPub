<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\IntransitiveActivity;

class Arrive extends IntransitiveActivity
{
    public string $type {
        get => 'Arrive';
    }
}
