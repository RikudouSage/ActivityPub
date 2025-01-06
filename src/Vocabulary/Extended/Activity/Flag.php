<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see Flag::actor} is "flagging" the {@see Flag::object}.
 * Flagging is defined in the sense common to many social platforms as reporting content as being inappropriate for any number of reasons.
 */
class Flag extends Activity
{
    public string $type {
        get => 'Flag';
    }
}
