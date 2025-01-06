<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;

/**
 * Represents a short written work typically less than a single paragraph in length.
 */
class Note extends BaseObject
{
    public string $type {
        get => 'Note';
    }
}
