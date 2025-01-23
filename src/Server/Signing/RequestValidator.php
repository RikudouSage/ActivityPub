<?php

namespace Rikudou\ActivityPub\Server\Signing;

use Psr\Http\Message\ServerRequestInterface;
use Rikudou\ActivityPub\Server\Abstraction\LocalActor;

interface RequestValidator
{
    public function isRequestValid(ServerRequestInterface $request, ?LocalActor $localActor = null): bool;
}
