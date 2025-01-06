<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

class Block extends Ignore
{
    public string $type {
        get => 'Block';
    }
}
