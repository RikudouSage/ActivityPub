<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;

/**
 * Represents any kind of multi-paragraph written work.
 */
class Article extends BaseObject
{
    public string $type {
        get => 'Article';
    }
}
