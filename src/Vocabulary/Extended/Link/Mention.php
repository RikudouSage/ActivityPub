<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Link;

use Rikudou\ActivityPub\Vocabulary\Core\Link;

/**
 * A specialized {@see Link} that represents an @mention.
 */
class Mention extends Link
{
    public string $type {
        get => 'Mention';
    }
}
