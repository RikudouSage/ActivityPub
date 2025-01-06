<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see Offer::actor} is offering the {@see Offer::object}.
 * If specified, the {@see Offer::target} indicates the entity to which the {@see Offer::object} is being offered.
 */
class Offer extends Activity
{
    public string $type {
        get => 'Offer';
    }
}
