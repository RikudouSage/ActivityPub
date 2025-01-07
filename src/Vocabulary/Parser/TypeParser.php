<?php

namespace Rikudou\ActivityPub\Vocabulary\Parser;

use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;

interface TypeParser
{
    /**
     * @param array<string> $data
     */
    public function parse(array $data): ActivityPubObject;

    public function parseJson(string $data): ActivityPubObject;
}
