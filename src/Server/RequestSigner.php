<?php

namespace Rikudou\ActivityPub\Server;

use Psr\Http\Message\RequestInterface;
use SensitiveParameter;

interface RequestSigner
{
    public function signRequest(RequestInterface $request, string $keyId, #[SensitiveParameter] string $privateKeyPem): RequestInterface;
}
