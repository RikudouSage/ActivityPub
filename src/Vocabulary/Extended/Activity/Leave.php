<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

class Leave extends Activity
{
    public string $type {
        get => 'Leave';
    }
}
