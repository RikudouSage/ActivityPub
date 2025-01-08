<?php

namespace Rikudou\ActivityPub\Server\ObjectFetcher;

use Rikudou\ActivityPub\Dto\WebFingerResponse;

interface WebFinger
{
    public function find(string $account): WebFingerResponse;
}
