<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use DateTimeImmutable;
use DateTimeInterface;
use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;

class Tombstone extends BaseObject
{
    public string $type {
        get => 'Tombstone';
    }

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
