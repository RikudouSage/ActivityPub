<?php

namespace Rikudou\ActivityPub\Vocabulary\Contract;

use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Dto\Endpoints;
use Rikudou\ActivityPub\Dto\PublicKey;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

interface ActivityPubActor extends ActivityPubObject
{
    /**
     * A reference to an {@see OrderedCollection} comprised of all the messages received by the actor. Also the
     * target of all incoming messages for the actor.
     */
    #[RequiredProperty(ValidatorMode::Lax)]
    public ?Link $inbox {
        get;
        set (Link|null|string $value);
    }

    /**
     * An {@see OrderedCollection} comprised of all the messages produced by the actor
     */
    #[RequiredProperty(ValidatorMode::Strict)]
    public ?Link $outbox {
        get;
        set (Link|null|string $value);
    }

    /**
     * A link to an {@see Collection} of the actors that this actor is following
     */
    #[RequiredProperty(ValidatorMode::Strict)]
    public ?Link $following {
        get;
        set (Link|null|string $value);
    }

    /**
     * A link to an {@see Collection} of the actors that follow this actor
     */
    #[RequiredProperty(ValidatorMode::Strict)]
    public ?Link $followers {
        get;
        set (Link|null|string $value);
    }

    /**
     * A link to a {@see Collection} of objects this actor has liked
     */
    public ?Link $liked {
        get;
        set (Link|null|string $value);
    }

    /**
     * A list of supplementary Collections which may be of interest.
     *
     * @var array<Link>
     */
    public ?array $streams {
        get;
        set;
    }

    /**
     * A short username which may be used to refer to the actor, with no uniqueness guarantees.
     */
    #[RequiredProperty(ValidatorMode::Recommended)]
    public ?string $preferredUsername {
        get;
        set;
    }

    /**
     * A json object which maps additional (typically server/domain-wide) endpoints which may be useful either for this actor or someone referencing this actor.
     * This mapping may be nested inside the actor document as the value or may be a link to a JSON-LD document with these properties.
     */
    public Endpoints|Link|null $endpoints {
        get;
        set (Endpoints|Link|null|string $value);
    }

    /**
     * The public key for the user, used for authenticating every request originating from this actor.
     */
    #[RequiredProperty(ValidatorMode::Recommended)]
    public ?PublicKey $publicKey {
        get;
        set;
    }
}
