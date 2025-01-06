<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see Join::actor} has joined the {@see Join::object}.
 * The {@see Join::target} and {@see Join::origin} typically have no defined meaning.
 */
class Join extends Activity
{
    public string $type {
        get => 'Join';
    }
}
