<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

class Like extends Activity
{
    public string $type {
        get => 'Like';
    }
}
