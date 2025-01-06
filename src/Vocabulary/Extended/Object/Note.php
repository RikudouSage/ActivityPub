<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;

class Note extends BaseObject
{
    public string $type {
        get => 'Note';
    }
}
