<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

class Delete extends Activity
{
    public string $type {
        get => 'Delete';
    }
}
