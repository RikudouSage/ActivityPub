<?php

namespace Rikudou\ActivityPub\Dto;

use JsonSerializable;

final readonly class Endpoints implements JsonSerializable
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

    public function jsonSerialize(): array
    {
        $result = [];
        if ($this->proxyUrl) {
            $result['proxyUrl'] = $this->proxyUrl;
        }
        if ($this->oauthAuthorizationEndpoint) {
            $result['oauthAuthorizationEndpoint'] = $this->oauthAuthorizationEndpoint;
        }
        if ($this->oauthTokenEndpoint) {
            $result['oauthTokenEndpoint'] = $this->oauthTokenEndpoint;
        }
        if ($this->provideClientKey) {
            $result['provideClientKey'] = $this->provideClientKey;
        }
        if ($this->signClientKey) {
            $result['signClientKey'] = $this->signClientKey;
        }
        if ($this->sharedInbox) {
            $result['sharedInbox'] = $this->sharedInbox;
        }

        return $result;
    }
}
