<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

class Flag extends Activity
{
    public string $type {
        get => 'Flag';
    }
}
