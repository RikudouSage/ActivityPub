<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

class Dislike extends Activity
{
    public string $type {
        get => 'Dislike';
    }
}
