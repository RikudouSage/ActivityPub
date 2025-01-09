<?php

namespace Rikudou\ActivityPub\Vocabulary\Parser;

use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

interface TypeParser
{
    /**
     * @param array<string> $data
     */
    public function parse(array $data, bool $allowCustomProperties = false): ActivityPubObject|Link;

    public function parseJson(string $data, bool $allowCustomProperties = false): ActivityPubObject|Link;

    /**
     * @param string $typeName
     * @param class-string<ActivityPubObject|Link> $class
     */
    public function registerType(string $typeName, string $class): void;
}
