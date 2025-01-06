<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\IntransitiveActivity;

/**
 * Indicates that the {@see Arrive::actor} has arrived at the {@see Arrive::location}.
 * The {@see Arrive::origin} can be used to identify the context from which the {@see Arrive::actor} originated.
 * The {@see Arrive::target} typically has no defined meaning.
 */
class Arrive extends IntransitiveActivity
{
    public string $type {
        get => 'Arrive';
    }
}
