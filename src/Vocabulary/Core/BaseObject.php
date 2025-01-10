<?php

namespace Rikudou\ActivityPub\Vocabulary\Core;

use DateInterval;
use DateTimeImmutable;
use DateTimeInterface;
use Rikudou\ActivityPub\ActivityPubConstants;
use Rikudou\ActivityPub\Attribute\LangMapProperty;
use Rikudou\ActivityPub\Attribute\ObjectArray;
use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Attribute\SerializedName;
use Rikudou\ActivityPub\Dto\NullID;
use Rikudou\ActivityPub\Dto\OmittedID;
use Rikudou\ActivityPub\Dto\Source\Source;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Trait\JsonSerializableObjectTrait;
use Rikudou\ActivityPub\Trait\ObjectSetterTrait;
use Rikudou\ActivityPub\Validator\AllIterableChildrenValidator;
use Rikudou\ActivityPub\Validator\CompoundValidator;
use Rikudou\ActivityPub\Validator\Condition\AndCondition;
use Rikudou\ActivityPub\Validator\Condition\IsArray;
use Rikudou\ActivityPub\Validator\Condition\IsInstanceOf;
use Rikudou\ActivityPub\Validator\Condition\IsIterable;
use Rikudou\ActivityPub\Validator\Condition\IsString;
use Rikudou\ActivityPub\Validator\Condition\IsValidatorMode;
use Rikudou\ActivityPub\Validator\Condition\NotNull;
use Rikudou\ActivityPub\Validator\ConditionalValidator;
use Rikudou\ActivityPub\Validator\GlobMatchValidator;
use Rikudou\ActivityPub\Validator\IsArrayValidator;
use Rikudou\ActivityPub\Validator\IsInstanceOfValidator;
use Rikudou\ActivityPub\Validator\IsStringValidator;
use Rikudou\ActivityPub\Validator\OrValidator;
use Rikudou\ActivityPub\Validator\UriValidator;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubCollection;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;
use Rikudou\ActivityPub\Vocabulary\Extended\Activity\Announce;
use Rikudou\ActivityPub\Vocabulary\Extended\Activity\Like;
use Rikudou\ActivityPub\Vocabulary\Extended\Actor\Application;
use Rikudou\ActivityPub\Vocabulary\Extended\Object\Image;
use Rikudou\ActivityPub\Vocabulary\Extended\Object\Place;

/**
 * Describes an object of any kind.
 * The Object type serves as the base type for most of the other kinds of objects defined in the Activity Vocabulary,
 * including other Core types such as {@see Activity}, {@see IntransitiveActivity}, {@see Collection} and {@see OrderedCollection}.
 */
class BaseObject implements ActivityPubObject
{
    use ObjectSetterTrait;
    use JsonSerializableObjectTrait;

    /**
     * Identifies the context within which the object exists or an activity was performed.
     *
     * The context should always contain {@see ActivityPubConstants::DEFAULT_NAMESPACE} and may include additional
     * contexts.
     *
     * @var array<string>|string
     */
    #[SerializedName('@context')]
    #[RequiredProperty(ValidatorMode::Lax)]
    public string|array $context = ActivityPubConstants::DEFAULT_NAMESPACE {
        get => $this->context;
        set {
            if ($this->__directSet) {
                $this->context = $value;
            } else {
                $this->set('context', $value);
            }
        }
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
    #[RequiredProperty(ValidatorMode::Strict)]
    public string|null|NullID|OmittedID $id = null {
        get => $this->id;
        set {
            if ($this->__directSet) {
                $this->id = $value;
            } else {
                $this->set('id', $value);
            }
        }
    }

    /**
     * Identifies a resource attached or related to an object that potentially requires special handling.
     * The intent is to provide a model that is at least semantically similar to attachments in email.
     *
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    #[ObjectArray]
    public ActivityPubObject|Link|array|null $attachment = null {
        get => $this->attachment;
        set (ActivityPubObject|Link|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->attachment = $value;
            } else {
                $this->set('attachment', $value);
            }
        }
    }

    /**
     * Identifies one or more entities to which this object is attributed.
     * The attributed entities might not be {@see Actor}.
     * For instance, an object might be attributed to the completion of another activity.
     *
     * @var Link|ActivityPubObject|array<Link|ActivityPubObject>|null
     */
    #[ObjectArray]
    public Link|ActivityPubObject|array|null $attributedTo = null {
        get => $this->attributedTo;
        set (Link|ActivityPubObject|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->attributedTo = $value;
            } else {
                $this->set('attributedTo', $value);
            }
        }
    }

    /**
     * Identifies one or more entities that represent the total population of entities for which the object can be
     * considered to be relevant.
     *
     * @var Link|ActivityPubObject|array<Link|ActivityPubObject>|null
     */
    #[ObjectArray]
    public Link|ActivityPubObject|array|null $audience = null {
        get => $this->audience;
        set (Link|ActivityPubObject|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->audience = $value;
            } else {
                $this->set('audience', $value);
            }
        }
    }

    /**
     * The content or textual representation of the Object encoded as a JSON string. By default, the value of content is HTML.
     * The {@see BaseObject::mediaType} property can be used in the object to indicate a different content type.
     *
     * The content MAY be expressed using multiple language-tagged values where language code is the key and the value is the content.
     *
     * @var string|array<string>|null
     */
    #[LangMapProperty]
    public string|array|null $content = null {
        get => $this->content;
        set {
            if ($this->__directSet) {
                $this->content = $value;
            } else {
                $this->set('content', $value);
            }
        }
    }

    /**
     * A simple, human-readable, plain-text name for the object.
     * HTML markup must not be included. The name may be expressed using multiple language-tagged values
     * where language code is the key and the value is the content.
     *
     * @var string|array<string>|null
     */
    #[LangMapProperty]
    public string|array|null $name = null {
        get => $this->name;
        set {
            if ($this->__directSet) {
                $this->name = $value;
            } else {
                $this->set('name', $value);
            }
        }
    }

    /**
     * The date and time describing the actual or expected ending time of the object.
     * When used with an Activity object, for instance, the endTime property specifies the moment the activity concluded or is expected to conclude.
     */
    public DateTimeInterface|null $endTime = null {
        get => $this->endTime;
        set (DateTimeInterface|null|string $value) {
            if (is_string($value)) {
                $value = new DateTimeImmutable($value);
            }

            if ($this->__directSet) {
                $this->endTime = $value;
            } else {
                $this->set('endTime', $value);
            }
        }
    }

    /**
     * Identifies the entity (e.g. an {@see Application}) that generated the object.
     */
    public ActivityPubObject|Link|null $generator = null {
        get => $this->generator;
        set (ActivityPubObject|Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->generator = $value;
            } else {
                $this->set('generator', $value);
            }
        }
    }

    /**
     * Indicates an entity that describes an icon for this object. The image should have an aspect ratio of one (horizontal)
     * to one (vertical) and should be suitable for presentation at a small size.
     *
     * @var Image|Link|array<Image|Link>|null
     */
    #[ObjectArray]
    public Image|Link|array|null $icon = null {
        get => $this->icon;
        set (Image|Link|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->icon = $value;
            } else {
                $this->set('icon', $value);
            }
        }
    }

    /**
     * An image document of any kind.
     *
     * @var Image|Link|array<Image|Link>|null
     */
    #[ObjectArray]
    public Image|Link|array|null $image = null {
        get => $this->image;
        set (Image|Link|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->image = $value;
            } else {
                $this->set('image', $value);
            }
        }
    }

    /**
     * Indicates one or more entities for which this object is considered a response.
     *
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    #[ObjectArray]
    public ActivityPubObject|Link|array|null $inReplyTo = null {
        get => $this->inReplyTo;
        set (ActivityPubObject|Link|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->inReplyTo = $value;
            } else {
                $this->set('inReplyTo', $value);
            }
        }
    }

    /**
     * Indicates one or more physical or logical locations associated with the object.
     *
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    #[ObjectArray]
    public ActivityPubObject|Link|array|null $location = null {
        get => $this->location;
        set (ActivityPubObject|Link|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->location = $value;
            } else {
                $this->set('location', $value);
            }
        }
    }

    /**
     * Identifies an entity that provides a preview of this object.
     */
    public ActivityPubObject|Link|null $preview = null {
        get => $this->preview;
        set (ActivityPubObject|Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->preview = $value;
            } else {
                $this->set('preview', $value);
            }
        }
    }

    /**
     * The date and time at which the object was published
     */
    public DateTimeInterface|null $published = null {
        get => $this->published;
        set (DateTimeInterface|null|string $value) {
            if (is_string($value)) {
                $value = new DateTimeImmutable($value);
            }

            if ($this->__directSet) {
                $this->published = $value;
            } else {
                $this->set('published', $value);
            }
        }
    }

    /**
     * Identifies a {@see Collection} containing objects considered to be responses to this object.
     */
    public ActivityPubCollection|null $replies = null {
        get => $this->replies;
        set {
            if ($this->__directSet) {
                $this->replies = $value;
            } else {
                $this->set('replies', $value);
            }
        }
    }

    /**
     * The date and time describing the actual or expected starting time of the object. When used with an {@see Activity} object,
     * for instance, the {@see BaseObject::startTime} property specifies the moment the activity began or is scheduled to begin.
     */
    public DateTimeInterface|null $startTime = null {
        get => $this->startTime;
        set (DateTimeInterface|null|string $value) {
            if (is_string($value)) {
                $value = new DateTimeImmutable($value);
            }

            if ($this->__directSet) {
                $this->startTime = $value;
            } else {
                $this->set('startTime', $value);
            }
        }
    }

    /**
     * A natural language summarization of the object encoded as HTML. Multiple language tagged summaries may be provided.
     *
     * @var string|array<string>|null
     */
    #[LangMapProperty]
    public string|array|null $summary = null {
        get => $this->summary;
        set {
            if ($this->__directSet) {
                $this->summary = $value;
            } else {
                $this->set('summary', $value);
            }
        }
    }

    /**
     * One or more "tags" that have been associated with an objects. A tag can be any kind of {@see BaseObject}.
     * The key difference between {@see BaseObject::attachment} and {@see BaseObject::tag} is that the former implies association by inclusion,
     * while the latter implies associated by reference.
     *
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    #[ObjectArray]
    public ActivityPubObject|Link|array|null $tag = null {
        get => $this->tag;
        set (ActivityPubObject|Link|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->tag = $value;
            } else {
                $this->set('tag', $value);
            }
        }
    }

    /**
     * The date and time at which the object was updated
     */
    public ?DateTimeInterface $updated = null {
        get => $this->updated;
        set (DateTimeInterface|null|string $value) {
            if (is_string($value)) {
                $value = new DateTimeImmutable($value);
            }

            if ($this->__directSet) {
                $this->updated = $value;
            } else {
                $this->set('updated', $value);
            }
        }
    }

    /**
     * Identifies one or more links to representations of the object.
     *
     * @var Link|array<Link>|null
     */
    #[ObjectArray]
    public Link|array|null $url = null {
        get => $this->url;
        set (Link|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->url = $value;
            } else {
                $this->set('url', $value);
            }
        }
    }

    /**
     * Identifies an entity considered to be part of the public primary audience of an Object
     *
     * @var array<ActivityPubObject|Link>|null
     */
    #[ObjectArray]
    public ?array $to = null {
        get => $this->to;
        set (ActivityPubObject|Link|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (!is_array($value) && $value !== null) {
                $value = [$value];
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->to = $value;
            } else {
                $this->set('to', $value);
            }
        }
    }

    /**
     * Identifies an Object that is part of the private primary audience of this Object.
     *
     * @var array<ActivityPubObject|Link>|null
     */
    #[ObjectArray]
    public array|null $bto = null {
        get => $this->bto;
        set (ActivityPubObject|Link|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (!is_array($value) && $value !== null) {
                $value = [$value];
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->bto = $value;
            } else {
                $this->set('bto', $value);
            }
        }
    }

    /**
     * Identifies an Object that is part of the public secondary audience of this Object.
     *
     * @var array<ActivityPubObject|Link>|null
     */
    #[ObjectArray]
    public array|null $cc = null {
        get => $this->cc;
        set (ActivityPubObject|Link|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (!is_array($value) && $value !== null) {
                $value = [$value];
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->cc = $value;
            } else {
                $this->set('cc', $value);
            }
        }
    }

    /**
     * Identifies one or more Objects that are part of the private secondary audience of this Object.
     *
     * @var array<ActivityPubObject|Link>|null
     */
    #[ObjectArray]
    public array|null $bcc = null {
        get => $this->bcc;
        set (ActivityPubObject|Link|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (!is_array($value) && $value !== null) {
                $value = [$value];
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->bcc = $value;
            } else {
                $this->set('bcc', $value);
            }
        }
    }

    /**
     * Identifies the MIME media type of the value of the {@see BaseObject::content} property.
     * If not specified, the {@see BaseObject::content} property is assumed to contain text/html content.
     */
    public ?string $mediaType = null {
        get => $this->mediaType;
        set {
            if ($this->__directSet) {
                $this->mediaType = $value;
            } else {
                $this->set('mediaType', $value);
            }
        }
    }

    /**
     * When the object describes a time-bound resource, such as an audio or video, a meeting, etc, the duration property indicates the object's approximate duration.
     */
    public ?DateInterval $duration = null {
        get => $this->duration;
        set (DateInterval|null|string $value) {
            if (is_string($value)) {
                $value = new DateInterval($value);
            }

            if ($this->__directSet) {
                $this->duration = $value;
            } else {
                $this->set('duration', $value);
            }
        }
    }

    /**
     * The source property is intended to convey some sort of source from which the {@see BaseObject::content} markup was derived,
     * as a form of provenance, or to support future editing by clients. In general, clients do the conversion from source to {@see BaseObject::content},
     * not the other way around.
     */
    public ?Source $source = null {
        get => $this->source;
        set {
            if ($this->__directSet) {
                $this->source = $value;
            } else {
                $this->set('source', $value);
            }
        }
    }

    /**
     * This is a list of all {@see Like} activities with this object as the {@see BaseObject::object} property.
     * The likes collection must be either an {@see OrderedCollection} or a {@see Collection} and may be filtered on privileges
     * of an authenticated user or as appropriate when no authentication is given.
     */
    public ?Link $likes = null {
        get => $this->likes;
        set (Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->likes = $value;
            } else {
                $this->set('likes', $value);
            }
        }
    }

    /**
     * This is a list of all {@see Announce} activities with this object as the {@see BaseObject::object} property.
     * The shares collection must be either an {@see OrderedCollection} or a {@see Collection} and may be filtered on privileges
     * of an authenticated user or as appropriate when no authentication is given.
     */
    public ?Link $shares = null {
        get => $this->shares;
        set (Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->shares = $value;
            } else {
                $this->set('shares', $value);
            }
        }
    }

    protected function getValidators(): iterable {
        return [
            'id' => new OrValidator(
                new UriValidator(),
                new IsInstanceOfValidator(OmittedID::class),
                new IsInstanceOfValidator(NullID::class),
            ),
            'context' => new CompoundValidator(
                new ConditionalValidator(
                    new IsString(),
                    new UriValidator(),
                ),
                new ConditionalValidator(
                    new IsArray(),
                    new AllIterableChildrenValidator(
                        new OrValidator(
                            new UriValidator(),
                            new CompoundValidator(
                                new IsArrayValidator(),
                                new AllIterableChildrenValidator(
                                    new UriValidator(),
                                ),
                            ),
                        ),
                    ),
                ),
            ),
            'attachment' => new ConditionalValidator(
                new IsArray(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(ActivityPubObject::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
            'attributedTo' => new ConditionalValidator(
                new IsArray(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(ActivityPubObject::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
            'audience' => new ConditionalValidator(
                new IsArray(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(ActivityPubObject::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
            'content' => new ConditionalValidator(
                new IsArray(),
                new AllIterableChildrenValidator(new IsStringValidator()),
            ),
            'generator' => new CompoundValidator(
                new ConditionalValidator(
                    new IsString(),
                    new UriValidator(),
                ),
                new ConditionalValidator(
                    new AndCondition(
                        new IsValidatorMode(ValidatorMode::Recommended),
                        new IsInstanceOf(ActivityPubObject::class),
                    ),
                    new IsInstanceOfValidator(ActivityPubActor::class),
                ),
            ),
            'icon' => new ConditionalValidator(
                new IsArray(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(Image::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
            'image' => new ConditionalValidator(
                new IsArray(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(Image::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
            'inReplyTo' => new ConditionalValidator(
                new IsArray(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(ActivityPubObject::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
            'location' => new CompoundValidator(
                new ConditionalValidator(
                    new IsArray(),
                    new AllIterableChildrenValidator(
                        new OrValidator(
                            new IsInstanceOfValidator(ActivityPubObject::class),
                            new IsInstanceOfValidator(Link::class),
                        ),
                    ),
                ),
                new ConditionalValidator(
                    new AndCondition(
                        new IsValidatorMode(ValidatorMode::Recommended),
                        new IsInstanceOfValidator(ActivityPubObject::class),
                    ),
                    // todo validate this for array children as well
                    new IsInstanceOfValidator(Place::class),
                )
            ),
            'replies' => new ConditionalValidator(
                new IsIterable(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(ActivityPubObject::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
            'summary' => new ConditionalValidator(
                new IsArray(),
                new AllIterableChildrenValidator(new IsStringValidator()),
            ),
            'tag' => new ConditionalValidator(
                new IsArray(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(ActivityPubObject::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
            'url' => new ConditionalValidator(
                new IsArray(),
                new AllIterableChildrenValidator(
                    new IsInstanceOfValidator(Link::class),
                ),
            ),
            'to' => new ConditionalValidator(
                new NotNull(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(ActivityPubObject::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
            'bto' => new ConditionalValidator(
                new NotNull(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(ActivityPubObject::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
            'cc' => new ConditionalValidator(
                new NotNull(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(ActivityPubObject::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
            'bcc' => new ConditionalValidator(
                new NotNull(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(ActivityPubObject::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
            'mediaType' => new ConditionalValidator(
                new NotNull(),
                new GlobMatchValidator('*/*'),
            ),
        ];
    }

    public string $type {
        get => 'Object';
    }

    /**
     * @param array<ActivityPubActor|Link|string> $array
     * @return array<ActivityPubActor|Link>
     */
    protected function convertStringArrayToLinkArray(array $array): array
    {
        return array_map(function (ActivityPubActor|Link|string $item): ActivityPubActor|Link {
            if (is_string($item)) {
                $item = Link::fromString($item);
            }

            return $item;
        }, $array);
    }
}
