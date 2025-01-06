<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see Listen::actor} has listened to the {@see Listen::object}.
 */
class Listen extends Activity
{
    public string $type {
        get => 'Listen';
    }
}
