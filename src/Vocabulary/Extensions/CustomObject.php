<?php

namespace Rikudou\ActivityPub\Vocabulary\Extensions;

use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;

final class CustomObject extends BaseObject
{
    private string $_type;

    public string $type {
        get => $this->_type;
    }

    public function __construct(
        string $type,
    ) {
        $this->_type = $type;
    }
}
