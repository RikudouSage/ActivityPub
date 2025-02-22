<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

/**
 * Represents an audio document of any kind.
 */
class Audio extends Document
{
    public string $type {
        get => 'Audio';
    }
}
