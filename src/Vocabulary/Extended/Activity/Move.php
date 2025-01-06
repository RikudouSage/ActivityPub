<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see Move::actor} has moved {@see Move::object} from {@see Move::origin} to {@see Move::target}.
 * If the {@see Move::origin} or {@see Move::target} are not specified, either can be determined by context.
 */
class Move extends Activity
{
    public string $type {
        get => 'Move';
    }
}
