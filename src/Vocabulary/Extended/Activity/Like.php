<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see Like::actor} likes, recommends or endorses the {@see Like::object}.
 * The {@see Like::target} and {@see Like::origin} typically have no defined meaning.
 */
class Like extends Activity
{
    public string $type {
        get => 'Like';
    }
}
