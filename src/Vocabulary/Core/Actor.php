<?php

namespace Rikudou\ActivityPub\Vocabulary\Core;

use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Dto\Endpoints;
use Rikudou\ActivityPub\Dto\PublicKey;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Validator\AllIterableChildrenValidator;
use Rikudou\ActivityPub\Validator\CompoundValidator;
use Rikudou\ActivityPub\Validator\Condition\IsValidatorMode;
use Rikudou\ActivityPub\Validator\Condition\NotNull;
use Rikudou\ActivityPub\Validator\ConditionalValidator;
use Rikudou\ActivityPub\Validator\IsInstanceOfValidator;
use Rikudou\ActivityPub\Validator\IsStringValidator;
use Rikudou\ActivityPub\Validator\OrValidator;
use Rikudou\ActivityPub\Validator\PropertyValueIsStringValidator;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;

class Actor extends BaseObject implements ActivityPubActor
{
    /**
     * A reference to an {@see OrderedCollection} comprised of all the messages received by the actor. Also the
     * target of all incoming messages for the actor.
     */
    #[RequiredProperty(ValidatorMode::Lax)]
    public ?Link $inbox = null {
        get => $this->inbox;
        set (Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->inbox = $value;
            } else {
                $this->set('inbox', $value);
            }
        }
    }

    /**
     * An {@see OrderedCollection} comprised of all the messages produced by the actor
     */
    #[RequiredProperty(ValidatorMode::Strict)]
    public ?Link $outbox = null {
        get => $this->outbox;
        set (Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->outbox = $value;
            } else {
                $this->set('outbox', $value);
            }
        }
    }

    /**
     * A link to an {@see Collection} of the actors that this actor is following
     */
    #[RequiredProperty(ValidatorMode::Strict)]
    public ?Link $following = null {
        get => $this->following;
        set (Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->following = $value;
            } else {
                $this->set('following', $value);
            }
        }
    }

    /**
     * A link to an {@see Collection} of the actors that follow this actor
     */
    #[RequiredProperty(ValidatorMode::Strict)]
    public ?Link $followers = null {
        get => $this->followers;
        set (Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->followers = $value;
            } else {
                $this->set('followers', $value);
            }
        }
    }

    /**
     * A link to a {@see Collection} of objects this actor has liked
     */
    public ?Link $liked = null {
        get => $this->liked;
        set (Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->liked = $value;
            } else {
                $this->set('liked', $value);
            }
        }
    }

    /**
     * A list of supplementary Collections which may be of interest.
     *
     * @var array<Link>
     */
    public ?array $streams = null {
        get => $this->streams;
        set {
            if ($this->__directSet) {
                $this->streams = $value;
            } else {
                $this->set('streams', $value);
            }
        }
    }

    /**
     * A short username which may be used to refer to the actor, with no uniqueness guarantees.
     */
    #[RequiredProperty(ValidatorMode::Recommended)]
    public ?string $preferredUsername = null {
        get => $this->preferredUsername;
        set {
            if ($this->__directSet) {
                $this->preferredUsername = $value;
            } else {
                $this->set('preferredUsername', $value);
            }
        }
    }

    /**
     * A json object which maps additional (typically server/domain-wide) endpoints which may be useful either for this actor or someone referencing this actor.
     * This mapping may be nested inside the actor document as the value or may be a link to a JSON-LD document with these properties.
     */
    public Endpoints|Link|null $endpoints = null {
        get => $this->endpoints;
        set (Endpoints|Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->endpoints = $value;
            } else {
                $this->set('endpoints', $value);
            }
        }
    }

    /**
     * The public key for the user, used for authenticating every request originating from this actor.
     */
    #[RequiredProperty(ValidatorMode::Recommended)]
    public ?PublicKey $publicKey = null {
        get => $this->publicKey;
        set {
            if ($this->__directSet) {
                $this->publicKey = $value;
            } else {
                $this->set('publicKey', $value);
            }
        }
    }

    protected function getValidators(): iterable
    {
        yield from parent::getValidators();
        yield from [
            'streams' => new ConditionalValidator(
                new NotNull(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsStringValidator(),
                        new IsInstanceOfValidator(Link::class),
                        new IsInstanceOfValidator(Collection::class),
                    ),
                ),
            ),
            'endpoints' => new CompoundValidator(
                new AllIterableChildrenValidator(
                    new IsStringValidator(),
                ),
                new ConditionalValidator(
                    new IsValidatorMode(ValidatorMode::Recommended),
                    new PropertyValueIsStringValidator('sharedInbox'),
                ),
            )
        ];
    }
}
