<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

class Join extends Activity
{
    public string $type {
        get => 'Join';
    }
}
