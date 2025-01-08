<?php

namespace Rikudou\ActivityPub\Dto;

use JsonSerializable;

final readonly class WebFingerLink implements JsonSerializable
{
    /**
     * @param array<string, string>|null $titles
     * @param array<string, string> $properties
     */
    public function __construct(
        public string $rel,
        public string $href,
        public ?string $type = null,
        public ?array $titles = null,
        public ?array $properties = null,
    ) {
    }

    public function jsonSerialize(): array
    {
        $response = [
            'rel' => $this->rel,
            'href' => $this->href,
        ];

        if ($this->type !== null) {
            $response['type'] = $this->type;
        }
        if ($this->titles !== null) {
            $response['titles'] = $this->titles;
        }
        if ($this->properties !== null) {
            $response['properties'] = $this->properties;
        }

        return $response;
    }
}
