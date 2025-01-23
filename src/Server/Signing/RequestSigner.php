<?php

namespace Rikudou\ActivityPub\Server\Signing;

use Psr\Http\Message\RequestInterface;
use Rikudou\ActivityPub\Server\Abstraction\LocalActor;
use SensitiveParameter;

interface RequestSigner
{
    public function signRequest(RequestInterface $request, LocalActor $actor): RequestInterface;
}
