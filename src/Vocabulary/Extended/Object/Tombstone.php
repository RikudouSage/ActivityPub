<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use DateTimeImmutable;
use DateTimeInterface;
use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;
use Rikudou\ActivityPub\Vocabulary\Core\Collection;

/**
 * A Tombstone represents a content object that has been deleted.
 * It can be used in {@see Collection} to signify that there used to be an object at this position, but it has been deleted.
 */
class Tombstone extends BaseObject
{
    public string $type {
        get => 'Tombstone';
    }

    /**
     * Identifies the type of the object that was deleted.
     */
    public ?string $formerType = null {
        get => $this->formerType;
        set {
            if ($this->__directSet) {
                $this->formerType = $value;
            } else {
                $this->set('formerType', $value);
            }
        }
    }

    /**
     * Timestamp for when the object was deleted.
     */
    public ?DateTimeInterface $deleted = null {
        get => $this->deleted;
        set (DateTimeInterface|null|string $value) {
            if (is_string($value)) {
                $value = new DateTimeImmutable($value);
            }

            if ($this->__directSet) {
                $this->deleted = $value;
            } else {
                $this->set('deleted', $value);
            }
        }
    }
}
