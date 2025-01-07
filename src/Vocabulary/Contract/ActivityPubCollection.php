<?php

namespace Rikudou\ActivityPub\Vocabulary\Contract;

use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Vocabulary\Core\CollectionPage;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

interface ActivityPubCollection extends ActivityPubObject
{
    /**
     * A non-negative integer specifying the total number of objects contained by the logical view of the collection.
     * This number might not reflect the actual number of items serialized within the Collection object instance.
     */
    #[RequiredProperty(ValidatorMode::Recommended)]
    public ?int $totalItems {
        get;
        set;
    }

    /**
     * In a paged Collection, indicates the page that contains the most recently updated member items.
     */
    public CollectionPage|Link|null $current {
        get;
        set (CollectionPage|Link|string|null $value);
    }

    /**
     * In a paged Collection, indicates the furthest preceding page of items in the collection.
     */
    public CollectionPage|Link|null $first {
        get;
        set (CollectionPage|Link|string|null $value);
    }

    /**
     * In a paged Collection, indicates the furthest proceeding page of the collection.
     */
    public CollectionPage|Link|null $last {
        get;
        set (CollectionPage|Link|string|null $value);
    }

    /**
     * Identifies the items contained in a collection. The items might be ordered or unordered.
     *
     * Note that PHP arrays are always ordered.
     *
     * @var array<ActivityPubObject|Link>|null
     */
    public ?array $items {
        get;
        set;
    }
}
