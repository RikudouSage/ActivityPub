<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

/**
 * Represents a video document of any kind.
 */
class Video extends Document
{
    public string $type {
        get => 'Video';
    }
}
