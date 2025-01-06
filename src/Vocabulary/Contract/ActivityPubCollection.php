<?php

namespace Rikudou\ActivityPub\Vocabulary\Contract;

use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Vocabulary\Core\CollectionPage;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

interface ActivityPubCollection extends ActivityPubObject
{
    #[RequiredProperty(ValidatorMode::Lax)]
    public ?int $totalItems = 0 {
        get;
        set;
    }

    /**
     * @var array<ActivityPubObject|Link>|null
     */
    public ?array $items = null {
        get;
        set;
    }

    public CollectionPage|Link|null $current = null {
        get;
        set (CollectionPage|Link|string|null $value);
    }

    public CollectionPage|Link|null $first = null {
        get;
        set (CollectionPage|Link|string|null $value);
    }

    public CollectionPage|Link|null $last = null {
        get;
        set (CollectionPage|Link|string|null $value);
    }
}
