<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

/**
 * Represents a Web Page.
 */
class Page extends Document
{
    public string $type {
        get => 'Page';
    }
}
