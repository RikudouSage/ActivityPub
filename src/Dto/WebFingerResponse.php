<?php

namespace Rikudou\ActivityPub\Dto;

use JsonSerializable;

final readonly class WebFingerResponse implements JsonSerializable
{
    /**
     * @param array<string> $aliases
     * @param array<string, string> $properties
     * @param array<WebFingerLink> $links
     */
    public function __construct(
        public string $subject,
        public ?array $aliases = null,
        public ?array $properties = null,
        public array  $links = [],
    ) {
    }

    public function jsonSerialize(): array
    {
        $result = [
            'subject' => $this->subject,
            'links' => $this->links,
        ];
        if ($this->aliases !== null) {
            $result['aliases'] = $this->aliases;
        }
        if ($this->properties !== null) {
            $result['properties'] = $this->properties;
        }

        return $result;
    }
}
