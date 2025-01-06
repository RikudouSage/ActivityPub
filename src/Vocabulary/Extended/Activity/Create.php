<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

class Create extends Activity
{
    public string $type {
        get => 'Create';
    }
}
