<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;

class Document extends BaseObject
{
    public string $type {
        get => 'Document';
    }
}
