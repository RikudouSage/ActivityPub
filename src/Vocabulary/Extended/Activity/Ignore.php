<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

class Ignore extends Activity
{
    public string $type {
        get => 'Ignore';
    }
}
