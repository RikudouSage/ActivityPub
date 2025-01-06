<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see Create::actor} has created the {@see Create::object}.
 */
class Create extends Activity
{
    public string $type {
        get => 'Create';
    }
}
