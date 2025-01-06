<?php

namespace Rikudou\ActivityPub\Dto;

final readonly class Endpoints
{
    public function __construct(
        public ?string $proxyUrl = null,
        public ?string $oauthAuthorizationEndpoint = null,
        public ?string $oauthTokenEndpoint = null,
        public ?string $provideClientKey = null,
        public ?string $signClientKey = null,
        public ?string $sharedInbox = null,
    ) {
    }
}
