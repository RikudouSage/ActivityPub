<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;

/**
 * Represents a document of any kind.
 */
class Document extends BaseObject
{
    public string $type {
        get => 'Document';
    }
}
