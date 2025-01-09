<?php

namespace Rikudou\ActivityPub\Vocabulary\Core;

use Rikudou\ActivityPub\Attribute\ObjectArray;
use Rikudou\ActivityPub\Attribute\SerializedName;
use Rikudou\ActivityPub\Validator\Condition\NotNull;
use Rikudou\ActivityPub\Validator\ConditionalValidator;
use Rikudou\ActivityPub\Validator\NonNegativeNumberValidator;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;

/**
 * Used to represent ordered subsets of items (a page) from an {@see OrderedCollection}.
 */
class OrderedCollectionPage extends CollectionPage
{
    public string $type {
        get => 'OrderedCollectionPage';
    }

    /**
     * @var array<ActivityPubObject|Link>|null
     */
    #[ObjectArray]
    #[SerializedName('orderedItems')]
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

    /**
     * A non-negative integer value identifying the relative position within the logical view of a strictly ordered collection.
     */
    public ?int $startIndex = null {
        get => $this->startIndex;
        set {
            if ($this->__directSet) {
                $this->startIndex = $value;
            } else {
                $this->set('startIndex', $value);
            }
        }
    }

    protected function getValidators(): iterable
    {
        yield from parent::getValidators();
        yield from [
            'startIndex' => new ConditionalValidator(
                new NotNull(),
                new NonNegativeNumberValidator(),
            ),
        ];
    }
}
