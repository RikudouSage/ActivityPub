<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

class Read extends Activity
{
    public string $type {
        get => 'Read';
    }
}
