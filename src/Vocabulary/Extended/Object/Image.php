<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;

/**
 * An image document of any kind
 */
class Image extends Document
{
    public string $type {
        get => 'Image';
    }
}
