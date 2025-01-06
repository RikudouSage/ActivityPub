<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\IntransitiveActivity;

/**
 * Indicates that the actor is traveling to {@see Travel::target} from {@see Travel::origin}.
 * Travel is an {@see IntransitiveActivity} whose {@see Travel::actor} specifies the direct object.
 * If the {@see Travel::target} or {@see Travel::origin} are not specified, either can be determined by context.
 */
class Travel extends IntransitiveActivity
{
    public string $type {
        get => 'Travel';
    }
}
