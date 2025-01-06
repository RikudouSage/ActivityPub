<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

class Listen extends Activity
{
    public string $type {
        get => 'Listen';
    }
}
