<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

/**
 * A specialization of {@see Offer} in which the {@see Invite::actor} is extending an invitation for the {@see Invite::object}
 * to the {@see Invite::target}.
 */
class Invite extends Offer
{
    public string $type {
        get => 'Invite';
    }
}
