<?php

namespace Rikudou\ActivityPub\Server\Signing;

use Psr\Http\Message\ServerRequestInterface;

interface RequestValidator
{
    public function isRequestValid(ServerRequestInterface $request): bool;
}
