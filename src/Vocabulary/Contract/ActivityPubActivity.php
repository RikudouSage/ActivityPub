<?php

namespace Rikudou\ActivityPub\Vocabulary\Contract;

use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Vocabulary\Core\Link;
use Rikudou\ActivityPub\Vocabulary\Extensions\RsaSignature2017;

interface ActivityPubActivity extends ActivityPubObject
{
    /**
     * Describes one or more entities that either performed or are expected to perform the activity.
     * Any single activity can have multiple actors. The actor may be specified using an indirect {@see Link}.
     *
     * @var ActivityPubActor|Link|array<ActivityPubActor|Link>|null
     */
    #[RequiredProperty(ValidatorMode::Lax)]
    public ActivityPubActor|Link|array|null $actor {
        get;
        set (ActivityPubActor|Link|array|null|string $value);
    }

    /**
     * Describes the direct object of the activity. For instance, in the activity "John added a movie to his wishlist",
     * the object of the activity is the movie added.
     *
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    public ActivityPubObject|Link|array|null $object {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * Describes the indirect object, or target, of the activity. The precise meaning of the target is largely dependent
     * on the type of action being described but will often be the object of the English preposition "to".
     * For instance, in the activity "John added a movie to his wishlist", the target of the activity is John's wishlist.
     * An activity can have more than one target.
     *
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    public ActivityPubObject|Link|array|null $target {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * Describes the result of the activity. For instance, if a particular action results in the creation of a new resource,
     * the result property can be used to describe that new resource.
     */
    public ActivityPubObject|Link|null $result {
        get;
        set (ActivityPubObject|Link|null|string $value);
    }

    /**
     * Describes an indirect object of the activity from which the activity is directed.
     * The precise meaning of the origin is the object of the English preposition "from".
     * For instance, in the activity "John moved an item to List B from List A", the origin of the activity is "List A".
     */
    public ActivityPubObject|Link|null $origin {
        get;
        set (ActivityPubObject|Link|null|string $value);
    }

    /**
     * Identifies one or more objects used (or to be used) in the completion of an Activity.
     *
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    public ActivityPubObject|Link|array|null $instrument {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    public ?RsaSignature2017 $signature = null {
        get;
        set;
    }
}
