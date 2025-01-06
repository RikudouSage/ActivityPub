<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;
use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

/**
 * Describes a relationship between two individuals.
 * The {@see Relationship::subject} and {@see Relationship::object} properties are used to identify the connected individuals.
 */
class Relationship extends BaseObject
{
    public string $type {
        get => 'Relationship';
    }

    /**
     * Identifies one of the connected individuals. For instance, for a Relationship object describing
     * "John is related to Sally", subject would refer to John.
     */
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

    /**
     * Identifies the kind of relationship that exists between {@see Relationship::subject} and {@see Relationship::object}.
     */
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

    /**
     * Describes the entity to which the {@see Relationship::subject} is related.
     */
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
