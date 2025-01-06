<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see Ignore::actor} is ignoring the {@see Ignore::object}.
 * The {@see Ignore::target} and {@see Ignore::origin} typically have no defined meaning.
 */
class Ignore extends Activity
{
    public string $type {
        get => 'Ignore';
    }
}
