<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see View::actor} has viewed the {@see View::object}.
 */
class View extends Activity
{
    public string $type {
        get => 'View';
    }
}
