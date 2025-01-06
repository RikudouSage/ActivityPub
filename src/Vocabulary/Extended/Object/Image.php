<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;

class Image extends Document
{
    public string $type {
        get => 'Image';
    }
}
