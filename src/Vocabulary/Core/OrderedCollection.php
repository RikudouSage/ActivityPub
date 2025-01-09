<?php

namespace Rikudou\ActivityPub\Vocabulary\Core;

use Rikudou\ActivityPub\Attribute\ObjectArray;
use Rikudou\ActivityPub\Attribute\SerializedName;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;

/**
 * A subtype of {@see Collection} in which members of the logical collection are assumed to always be strictly ordered.
 */
class OrderedCollection extends Collection
{
    public string $type {
        get => 'OrderedCollection';
    }

    /**
     * Identifies the items contained in a collection. The items might be ordered or unordered.
     *
     * Note that PHP arrays are always ordered.
     *
     * @var array<ActivityPubObject|Link>|null
     */
    #[ObjectArray]
    #[SerializedName('orderedItems')]
    public ?array $items = null {
        get => $this->items;
        set {
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->items = $value;
            } else {
                $this->set('items', $value);
            }
        }
    }
}
