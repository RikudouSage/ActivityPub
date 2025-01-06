<?php

namespace Rikudou\ActivityPub\Vocabulary\Contract;

use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

interface ActivityPubActivity extends ActivityPubObject
{
    /**
     * @var ActivityPubActor|Link|array<ActivityPubActor|Link>|null
     */
    #[RequiredProperty(ValidatorMode::Lax)]
    public ActivityPubActor|Link|array|null $actor {
        get;
        set (ActivityPubActor|Link|array|null|string $value);
    }

    /**
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    public ActivityPubObject|Link|array|null $object {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    public ActivityPubObject|Link|array|null $target {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    public ActivityPubObject|Link|null $result {
        get;
        set (ActivityPubObject|Link|null|string $value);
    }

    public ActivityPubObject|Link|null $origin {
        get;
        set (ActivityPubObject|Link|null|string $value);
    }

    /**
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    public ActivityPubObject|Link|array|null $instrument {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }
}
