<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Link;

use Rikudou\ActivityPub\Vocabulary\Core\Link;

class Mention extends Link
{
    public string $type {
        get => 'Mention';
    }
}
