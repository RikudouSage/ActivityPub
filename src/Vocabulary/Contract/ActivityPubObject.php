<?php

namespace Rikudou\ActivityPub\Vocabulary\Contract;

use DateInterval;
use DateTimeInterface;
use JsonSerializable;
use Rikudou\ActivityPub\Attribute\LangMapProperty;
use Rikudou\ActivityPub\Dto\NullID;
use Rikudou\ActivityPub\Dto\OmittedID;
use Rikudou\ActivityPub\Dto\Source\Source;
use Rikudou\ActivityPub\Vocabulary\Core\Link;
use Rikudou\ActivityPub\Vocabulary\Extended\Object\Image;

interface ActivityPubObject extends JsonSerializable
{
    /**
     * Identifies the context within which the object exists or an activity was performed.
     *
     * The context should always contain {@see ActivityPubConstants::DEFAULT_NAMESPACE} and may include additional
     * contexts.
     *
     * @var array<string>|string
     */
    public string|array $context {
        get;
        set;
    }

    /**
     * The unique type for each object
     */
    public string $type {
        get;
    }

    /**
     * All objects distributed by the ActivityPub protocol must have unique global identifiers, unless they are
     * intentionally transient.
     *
     * The ID might be omitted (using {@see OmittedID}) (indicating that this object is transient)
     * or explicitly set to {@see NullID} to indicate it's an anonymous object.
     *
     * Note that the PHP's null should not be used because it's impossible to distinguish between
     *  - unset default value (null)
     *  - intentionally omitted ({@see OmittedID})
     *  - intentionally kept as null ({@see NullID})
     */
    public string|null|NullID|OmittedID $id {
        get;
        set;
    }

    /**
     * Identifies a resource attached or related to an object that potentially requires special handling.
     * The intent is to provide a model that is at least semantically similar to attachments in email.
     *
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    public ActivityPubObject|Link|array|null $attachment {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * Identifies one or more entities to which this object is attributed.
     * The attributed entities might not be {@see Actor}.
     * For instance, an object might be attributed to the completion of another activity.
     *
     * @var Link|ActivityPubObject|array<Link|ActivityPubObject>|null
     */
    public Link|ActivityPubObject|array|null $attributedTo {
        get;
        set (Link|ActivityPubObject|array|null|string $value);
    }

    /**
     * Identifies one or more entities that represent the total population of entities for which the object can be
     * considered to be relevant.
     *
     * @var Link|ActivityPubObject|array<Link|ActivityPubObject>|null
     */
    public Link|ActivityPubObject|array|null $audience {
        get;
        set (Link|ActivityPubObject|array|null|string $value);
    }

    /**
     * The content or textual representation of the Object encoded as a JSON string. By default, the value of content is HTML.
     * The {@see BaseObject::mediaType} property can be used in the object to indicate a different content type.
     *
     * The content MAY be expressed using multiple language-tagged values where language code is the key and the value is the content.
     *
     * @var string|array<string>|null
     */
    public string|array|null $content {
        get;
        set;
    }

    /**
     * A simple, human-readable, plain-text name for the object.
     * HTML markup must not be included. The name may be expressed using multiple language-tagged values
     * where language code is the key and the value is the content.
     *
     * @var string|array<string>|null
     */
    #[LangMapProperty]
    public string|array|null $name {
        get;
        set;
    }

    /**
     * The date and time describing the actual or expected ending time of the object.
     * When used with an Activity object, for instance, the endTime property specifies the moment the activity concluded or is expected to conclude.
     */
    public DateTimeInterface|null $endTime {
        get;
        set (DateTimeInterface|null|string $value);
    }

    /**
     * Identifies the entity (e.g. an {@see Application}) that generated the object.
     */
    public ActivityPubObject|Link|null $generator {
        get;
        set (ActivityPubObject|Link|null|string $value);
    }

    /**
     * Indicates an entity that describes an icon for this object. The image should have an aspect ratio of one (horizontal)
     * to one (vertical) and should be suitable for presentation at a small size.
     *
     * @var Image|Link|array<Image|Link>|null
     */
    public Image|Link|array|null $icon {
        get;
        set (Image|Link|array|null|string $value);
    }

    /**
     * An image document of any kind.
     *
     * @var Image|Link|array<Image|Link>|null
     */
    public Image|Link|array|null $image {
        get;
        set (Image|Link|array|null|string $value);
    }

    /**
     * Indicates one or more entities for which this object is considered a response.
     *
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    public ActivityPubObject|Link|array|null $inReplyTo {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * Indicates one or more physical or logical locations associated with the object.
     *
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    public ActivityPubObject|Link|array|null $location {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * Identifies an entity that provides a preview of this object.
     */
    public ActivityPubObject|Link|null $preview {
        get;
        set (ActivityPubObject|Link|null|string $value);
    }

    /**
     * The date and time at which the object was published
     */
    public DateTimeInterface|null $published {
        get;
        set (DateTimeInterface|null|string $value);
    }

    /**
     * Identifies a {@see Collection} containing objects considered to be responses to this object.
     */
    public ActivityPubCollection|null $replies {
        get;
        set;
    }

    /**
     * The date and time describing the actual or expected starting time of the object. When used with an {@see Activity} object,
     * for instance, the {@see BaseObject::startTime} property specifies the moment the activity began or is scheduled to begin.
     */
    public DateTimeInterface|null $startTime {
        get;
        set (DateTimeInterface|null|string $value);
    }

    /**
     * A natural language summarization of the object encoded as HTML. Multiple language tagged summaries may be provided.
     *
     * @var string|array<string>|null
     */
    #[LangMapProperty]
    public string|array|null $summary {
        get;
        set;
    }

    /**
     * One or more "tags" that have been associated with an objects. A tag can be any kind of {@see BaseObject}.
     * The key difference between {@see BaseObject::attachment} and {@see BaseObject::tag} is that the former implies association by inclusion,
     * while the latter implies associated by reference.
     *
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    public ActivityPubObject|Link|array|null $tag {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * The date and time at which the object was updated
     */
    public DateTimeInterface|null $updated {
        get;
        set (DateTimeInterface|null|string $value);
    }

    /**
     * Identifies one or more links to representations of the object.
     *
     * @var Link|array<Link>|null
     */
    public Link|array|null $url {
        get;
        set (Link|array|null|string $value);
    }

    /**
     * Identifies an entity considered to be part of the public primary audience of an Object
     *
     * @var array<ActivityPubObject|Link>|null
     */
    public ?array $to {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * Identifies an Object that is part of the private primary audience of this Object.
     *
     * @var array<ActivityPubObject|Link>|null
     */
    public array|null $bto {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * Identifies an Object that is part of the public secondary audience of this Object.
     *
     * @var array<ActivityPubObject|Link>|null
     */
    public array|null $cc {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * Identifies one or more Objects that are part of the private secondary audience of this Object.
     *
     * @var array<ActivityPubObject|Link>|null
     */
    public array|null $bcc {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * Identifies the MIME media type of the value of the {@see BaseObject::content} property.
     * If not specified, the {@see BaseObject::content} property is assumed to contain text/html content.
     */
    public ?string $mediaType {
        get;
        set;
    }

    /**
     * When the object describes a time-bound resource, such as an audio or video, a meeting, etc, the duration property indicates the object's approximate duration.
     */
    public ?DateInterval $duration {
        get;
        set (DateInterval|null|string $value);
    }

    /**
     * The source property is intended to convey some sort of source from which the {@see BaseObject::content} markup was derived,
     * as a form of provenance, or to support future editing by clients. In general, clients do the conversion from source to {@see BaseObject::content},
     * not the other way around.
     */
    public ?Source $source {
        get;
        set;
    }

    /**
     * This is a list of all {@see Like} activities with this object as the {@see BaseObject::object} property.
     * The likes collection must be either an {@see OrderedCollection} or a {@see Collection} and may be filtered on privileges
     * of an authenticated user or as appropriate when no authentication is given.
     */
    public Link|ActivityPubCollection|null $likes {
        get;
        set (Link|null|string|ActivityPubCollection $value);
    }

    /**
     * This is a list of all {@see Announce} activities with this object as the {@see BaseObject::object} property.
     * The shares collection must be either an {@see OrderedCollection} or a {@see Collection} and may be filtered on privileges
     * of an authenticated user or as appropriate when no authentication is given.
     */
    public Link|ActivityPubCollection|null $shares {
        get;
        set (Link|null|string|ActivityPubCollection $value);
    }

    public function set(string $propertyName, mixed $value): void;
    public function get(string $propertyName): mixed;
}
