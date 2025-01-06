<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see Remove::actor} is removing the {@see Remove::object}.
 * If specified, the {@see Remove::origin} indicates the context from which the {@see Remove::object} is being removed.
 */
class Remove extends Activity
{
    public string $type {
        get => 'Remove';
    }
}
