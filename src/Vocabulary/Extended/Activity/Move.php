<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

class Move extends Activity
{
    public string $type {
        get => 'Move';
    }
}
