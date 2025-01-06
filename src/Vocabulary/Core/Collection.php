<?php

namespace Rikudou\ActivityPub\Vocabulary\Core;

use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Exception\InvalidStateException;
use Rikudou\ActivityPub\Validator\Condition\NotNull;
use Rikudou\ActivityPub\Validator\ConditionalValidator;
use Rikudou\ActivityPub\Validator\IsInstanceOfValidator;
use Rikudou\ActivityPub\Validator\NonNegativeNumberValidator;
use Rikudou\ActivityPub\Validator\OrValidator;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubCollection;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;

/**
 * A Collection is a subtype of {@see BaseObject} that represents ordered or unordered sets of {@see BaseObject} or {@see Link} instances.
 */
class Collection extends BaseObject implements ActivityPubCollection
{
    /**
     * A non-negative integer specifying the total number of objects contained by the logical view of the collection.
     * This number might not reflect the actual number of items serialized within the Collection object instance.
     */
    #[RequiredProperty(ValidatorMode::Recommended)]
    public ?int $totalItems = 0 {
        get => $this->totalItems;
        set {
            if ($this->__directSet) {
                $this->totalItems = $value;
            } else {
                $this->set('totalItems', $value);
            }
        }
    }

    /**
     * In a paged Collection, indicates the page that contains the most recently updated member items.
     */
    public CollectionPage|Link|null $current = null {
        get => $this->current;
        set (CollectionPage|Link|string|null $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->current = $value;
            } else {
                $this->set('currentPage', $value);
            }
        }
    }

    /**
     * In a paged Collection, indicates the furthest preceding page of items in the collection.
     */
    public CollectionPage|Link|null $first = null {
        get => $this->first;
        set (CollectionPage|Link|string|null $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->first = $value;
            } else {
                $this->set('first', $value);
            }
        }
    }

    /**
     * In a paged Collection, indicates the furthest proceeding page of the collection.
     */
    public CollectionPage|Link|null $last = null {
        get => $this->last;
        set (CollectionPage|Link|string|null $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->last = $value;
            } else {
                $this->set('last', $value);
            }
        }
    }

    /**
     * Identifies the items contained in a collection. The items might be ordered or unordered.
     *
     * Note that PHP arrays are always ordered.
     *
     * @var array<ActivityPubObject|Link>|null
     */
    public ?array $items = null {
        get => $this->items;
        set {
            if ($this->__directSet) {
                $this->items = $value;
            } else {
                $this->set('items', $value);
            }
        }
    }

    protected function getValidators(): iterable
    {
        yield from parent::getValidators();
        yield from [
            'totalItems' => new ConditionalValidator(
                new NotNull(),
                new NonNegativeNumberValidator(),
            ),
            'items' => new ConditionalValidator(
                new NotNull(),
                new OrValidator(
                    new IsInstanceOfValidator(Link::class),
                    new IsInstanceOfValidator(ActivityPubObject::class),
                ),
            ),
        ];
    }

    protected function validateEntity(): void
    {
        if ($this->validatorMode === ValidatorMode::Recommended) {
            if (
                $this->items !== null
                && ($this->first || $this->last || $this->current)
            ) {
                throw new InvalidStateException("You should only set items or first/last/currentPage, collection shouldn't be both paginated and unpaginated");
            }
        }
    }
}
