<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

/**
 * Indicates that the {@see Block::actor} is blocking the {@see Block::object}.
 * Blocking is a stronger form of {@see Ignore}. The typical use is to support social systems that allow one user to block
 * activities or content of other users. The {@see Block::target} and {@see Block::origin} typically have no defined meaning.
 */
class Block extends Ignore
{
    public string $type {
        get => 'Block';
    }
}
