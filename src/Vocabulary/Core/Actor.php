<?php

namespace Rikudou\ActivityPub\Vocabulary\Core;

use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Dto\Endpoints;
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

    public ?Endpoints $endpoints = null {
        get => $this->endpoints;
        set {
            if ($this->__directSet) {
                $this->endpoints = $value;
            } else {
                $this->set('endpoints', $value);
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
