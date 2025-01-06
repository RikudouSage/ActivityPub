<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

class Update extends Activity
{
    public string $type {
        get => 'Update';
    }
}
