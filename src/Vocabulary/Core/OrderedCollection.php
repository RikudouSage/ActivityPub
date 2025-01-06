<?php

namespace Rikudou\ActivityPub\Vocabulary\Core;

use Rikudou\ActivityPub\Attribute\SerializedName;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;

/**
 * A subtype of {@see Collection} in which members of the logical collection are assumed to always be strictly ordered.
 */
class OrderedCollection extends Collection
{
    /**
     * @var array<ActivityPubObject|Link>|null
     */
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
}
