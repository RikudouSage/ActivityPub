<?php

namespace Rikudou\ActivityPub\Vocabulary\Core;

use Rikudou\ActivityPub\Attribute\ObjectArray;
use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Validator\AllIterableChildrenValidator;
use Rikudou\ActivityPub\Validator\Condition\IsArray;
use Rikudou\ActivityPub\Validator\ConditionalValidator;
use Rikudou\ActivityPub\Validator\IsInstanceOfValidator;
use Rikudou\ActivityPub\Validator\OrValidator;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActivity;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;
use Rikudou\ActivityPub\Vocabulary\Extensions\RsaSignature2017;

/**
 * An Activity is a subtype of {@see BaseObject} that describes some form of action that may happen, is currently happening, or has already happened.
 * The Activity type itself serves as an abstract base type for all types of activities.
 * It is important to note that the Activity type itself does not carry any specific semantics about the kind of action being taken.
 */
class Activity extends BaseObject implements ActivityPubActivity
{
    public string $type {
        get => 'Activity';
    }

    /**
     * Describes one or more entities that either performed or are expected to perform the activity.
     * Any single activity can have multiple actors. The actor may be specified using an indirect {@see Link}.
     *
     * @var ActivityPubActor|Link|array<ActivityPubActor|Link>|null
     */
    #[ObjectArray]
    #[RequiredProperty(ValidatorMode::Recommended)]
    public ActivityPubActor|Link|array|null $actor = null {
        get => $this->actor;
        set (ActivityPubActor|Link|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->actor = $value;
            } else {
                $this->set('actor', $value);
            }
        }
    }

    /**
     * Describes the direct object of the activity. For instance, in the activity "John added a movie to his wishlist",
     * the object of the activity is the movie added.
     *
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    #[ObjectArray]
    #[RequiredProperty(ValidatorMode::Recommended)]
    public ActivityPubObject|Link|array|null $object = null {
        get => $this->object;
        set (ActivityPubObject|Link|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->object = $value;
            } else {
                $this->set('object', $value);
            }
        }
    }

    /**
     * Describes the indirect object, or target, of the activity. The precise meaning of the target is largely dependent
     * on the type of action being described but will often be the object of the English preposition "to".
     * For instance, in the activity "John added a movie to his wishlist", the target of the activity is John's wishlist.
     * An activity can have more than one target.
     *
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    #[ObjectArray]
    public ActivityPubObject|Link|array|null $target = null {
        get => $this->target;
        set (ActivityPubObject|Link|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->target = $value;
            } else {
                $this->set('target', $value);
            }
        }
    }

    /**
     * Describes the result of the activity. For instance, if a particular action results in the creation of a new resource,
     * the result property can be used to describe that new resource.
     */
    public ActivityPubObject|Link|null $result = null {
        get => $this->result;
        set (ActivityPubObject|Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->result = $value;
            } else {
                $this->set('result', $value);
            }
        }
    }

    /**
     * Describes an indirect object of the activity from which the activity is directed.
     * The precise meaning of the origin is the object of the English preposition "from".
     * For instance, in the activity "John moved an item to List B from List A", the origin of the activity is "List A".
     */
    public ActivityPubObject|Link|null $origin = null {
        get => $this->origin;
        set (ActivityPubObject|Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->origin = $value;
            } else {
                $this->set('origin', $value);
            }
        }
    }

    /**
     * Identifies one or more objects used (or to be used) in the completion of an Activity.
     *
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    #[ObjectArray]
    public ActivityPubObject|Link|array|null $instrument = null {
        get => $this->instrument;
        set (ActivityPubObject|Link|array|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }
            if (is_array($value)) {
                $value = $this->convertStringArrayToLinkArray($value);
            }

            if ($this->__directSet) {
                $this->instrument = $value;
            } else {
                $this->set('instrument', $value);
            }
        }
    }

    public ?RsaSignature2017 $signature = null {
        get => $this->signature;
        set {
            if ($this->__directSet) {
                $this->signature = $value;
            } else {
                $this->set('signature', $value);
            }
        }
    }

    protected function getValidators(): iterable
    {
        yield from parent::getValidators();
        yield from [
            'actor' => new ConditionalValidator(
                new IsArray(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(ActivityPubActor::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
            'object' => new ConditionalValidator(
                new IsArray(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(ActivityPubObject::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
            'target' => new ConditionalValidator(
                new IsArray(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(ActivityPubObject::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
            'instrument' => new ConditionalValidator(
                new IsArray(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(ActivityPubObject::class),
                        new IsInstanceOfValidator(Link::class),
                    ),
                ),
            ),
        ];
    }
}
