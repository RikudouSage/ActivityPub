<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see Delete::actor} has deleted the {@see Delete::object}.
 * If specified, the {@see Delete::origin} indicates the context from which the {@see Delete::object} was deleted.
 */
class Delete extends Activity
{
    public string $type {
        get => 'Delete';
    }
}
