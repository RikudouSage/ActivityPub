<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

class Audio extends Document
{
    public string $type {
        get => 'Audio';
    }
}
