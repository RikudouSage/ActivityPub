<?php

namespace Rikudou\ActivityPub\Server;

use Psr\Http\Message\ServerRequestInterface;

interface RequestValidator
{
    public function isRequestValid(ServerRequestInterface $request, string $publicKey): bool;
}
