<?php

namespace Rikudou\ActivityPub\Dto\Source;

final readonly class HtmlSource extends Source
{
    public function __construct(string $content)
    {
        parent::__construct($content, 'text/html');
    }
}
