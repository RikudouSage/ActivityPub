<?php

namespace Rikudou\ActivityPub\Vocabulary\Core;

use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Enum\ValidatorMode;

/**
 * Used to represent distinct subsets of items (a page) from a {@see Collection}.
 */
class CollectionPage extends Collection
{
    public string $type {
        get => 'CollectionPage';
    }

    /**
     * Identifies the {@see Collection} to which a CollectionPage objects items belong.
     */
    #[RequiredProperty(ValidatorMode::Recommended)]
    public Link|Collection|null $partOf = null {
        get => $this->partOf;
        set (Link|Collection|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->partOf = $value;
            } else {
                $this->set('partOf', $value);
            }
        }
    }

    /**
     * Indicates the next page of items.
     */
    #[RequiredProperty(ValidatorMode::Recommended)]
    public Link|CollectionPage|null $next = null {
        get => $this->next;
        set (Link|CollectionPage|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->next = $value;
            } else {
                $this->set('next', $value);
            }
        }
    }

    /**
     * Identifies the previous page of items.
     */
    #[RequiredProperty(ValidatorMode::Recommended)]
    public Link|CollectionPage|null $prev = null {
        get => $this->prev;
        set (Link|CollectionPage|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->prev = $value;
            } else {
                $this->set('prev', $value);
            }
        }
    }
}
