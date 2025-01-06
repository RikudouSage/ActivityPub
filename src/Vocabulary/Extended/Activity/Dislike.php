<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see Dislike::actor} dislikes the {@see Dislike::object}.
 */
class Dislike extends Activity
{
    public string $type {
        get => 'Dislike';
    }
}
