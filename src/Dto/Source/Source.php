<?php

namespace Rikudou\ActivityPub\Dto\Source;

use JsonSerializable;

readonly class Source implements JsonSerializable
{
    public function __construct(
        public string $content,
        public string $mediaType,
    ) {
    }

    public function jsonSerialize(): array
    {
        return [
            'content' => $this->content,
            'mediaType' => $this->mediaType,
        ];
    }
}
