<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see Leave::actor} has left the {@see Leave::object}.
 * The {@see Leave::target} and {@see Leave::origin} typically have no meaning.
 */
class Leave extends Activity
{
    public string $type {
        get => 'Leave';
    }
}
