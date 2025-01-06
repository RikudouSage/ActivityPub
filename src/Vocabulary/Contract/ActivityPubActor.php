<?php

namespace Rikudou\ActivityPub\Vocabulary\Contract;

use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Dto\Endpoints;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

interface ActivityPubActor extends ActivityPubObject
{
    #[RequiredProperty(ValidatorMode::Lax)]
    public ?Link $inbox = null {
        get;
        set (Link|null|string $value);
    }

    #[RequiredProperty(ValidatorMode::Strict)]
    public ?Link $outbox = null {
        get;
        set (Link|null|string $value);
    }

    #[RequiredProperty(ValidatorMode::Strict)]
    public ?Link $following = null {
        get;
        set (Link|null|string $value);
    }

    #[RequiredProperty(ValidatorMode::Strict)]
    public ?Link $followers = null {
        get;
        set (Link|null|string $value);
    }

    public ?Link $liked = null {
        get;
        set (Link|null|string $value);
    }

    public ?array $streams = null {
        get;
        set;
    }

    #[RequiredProperty(ValidatorMode::Recommended)]
    public ?string $preferredUsername = null {
        get;
        set;
    }

    public Endpoints|Link|null $endpoints = null {
        get;
        set (Endpoints|Link|null|string $value);
    }
}
