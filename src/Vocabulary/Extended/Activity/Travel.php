<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\IntransitiveActivity;

class Travel extends IntransitiveActivity
{
    public string $type {
        get => 'Travel';
    }
}
