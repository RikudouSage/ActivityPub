<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

class Remove extends Activity
{
    public string $type {
        get => 'Remove';
    }
}
