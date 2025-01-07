<?php

namespace Rikudou\ActivityPub\Vocabulary\Contract;

use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Dto\Endpoints;
use Rikudou\ActivityPub\Dto\PublicKey;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

interface ActivityPubActor extends ActivityPubObject
{
    #[RequiredProperty(ValidatorMode::Lax)]
    public ?Link $inbox {
        get;
        set (Link|null|string $value);
    }

    #[RequiredProperty(ValidatorMode::Strict)]
    public ?Link $outbox {
        get;
        set (Link|null|string $value);
    }

    #[RequiredProperty(ValidatorMode::Strict)]
    public ?Link $following {
        get;
        set (Link|null|string $value);
    }

    #[RequiredProperty(ValidatorMode::Strict)]
    public ?Link $followers {
        get;
        set (Link|null|string $value);
    }

    public ?Link $liked {
        get;
        set (Link|null|string $value);
    }

    public ?array $streams {
        get;
        set;
    }

    #[RequiredProperty(ValidatorMode::Recommended)]
    public ?string $preferredUsername {
        get;
        set;
    }

    public Endpoints|Link|null $endpoints {
        get;
        set (Endpoints|Link|null|string $value);
    }

    #[RequiredProperty(ValidatorMode::Recommended)]
    public ?PublicKey $publicKey {
        get;
        set;
    }
}
