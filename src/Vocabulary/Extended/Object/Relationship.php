<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;
use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

class Relationship extends BaseObject
{
    public string $type {
        get => 'Relationship';
    }

    public Link|ActivityPubObject|null $subject = null {
        get => $this->subject;
        set (Link|ActivityPubObject|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->subject = $value;
            } else {
                $this->set('subject', $value);
            }
        }
    }

    public Link|ActivityPubObject|null $relationship = null {
        get => $this->relationship;
        set (Link|ActivityPubObject|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->relationship = $value;
            } else {
                $this->set('relationship', $value);
            }
        }
    }

    public Link|ActivityPubObject|null $object = null {
        get => $this->object;
        set (ActivityPubObject|Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->object = $value;
            } else {
                $this->set('object', $value);
            }
        }
    }
}
