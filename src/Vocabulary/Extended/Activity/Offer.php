<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

class Offer extends Activity
{
    public string $type {
        get => 'Offer';
    }
}
