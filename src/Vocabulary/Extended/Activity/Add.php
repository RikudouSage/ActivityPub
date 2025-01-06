<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

class Add extends Activity
{
    public string $type {
        get => 'Add';
    }
}
