<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;
use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

class Profile extends BaseObject
{
    public string $type {
        get => 'Profile';
    }

    public ActivityPubObject|Link|null $describes = null {
        get => $this->describes;
        set (ActivityPubObject|Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->describes = $value;
            } else {
                $this->set('describes', $value);
            }
        }
    }
}
