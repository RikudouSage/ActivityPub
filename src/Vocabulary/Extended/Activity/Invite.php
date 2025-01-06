<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

class Invite extends Offer
{
    public string $type {
        get => 'Invite';
    }
}
