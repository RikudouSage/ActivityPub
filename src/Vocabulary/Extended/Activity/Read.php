<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see Read::actor} has read the {@see Read::object}.
 */
class Read extends Activity
{
    public string $type {
        get => 'Read';
    }
}
