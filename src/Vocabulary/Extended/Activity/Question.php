<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use DateTimeInterface;
use Rikudou\ActivityPub\Attribute\ObjectArray;
use Rikudou\ActivityPub\Validator\AllIterableChildrenValidator;
use Rikudou\ActivityPub\Validator\Condition\NotNull;
use Rikudou\ActivityPub\Validator\ConditionalValidator;
use Rikudou\ActivityPub\Validator\IsInstanceOfValidator;
use Rikudou\ActivityPub\Validator\OrValidator;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;
use Rikudou\ActivityPub\Vocabulary\Core\IntransitiveActivity;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

/**
 * Represents a question being asked.
 * Either of the {@see Question::anyOf} and {@see Question::oneOf} properties may be used to express possible answers,
 * but a Question object must not have both properties.
 */
class Question extends IntransitiveActivity
{
    public string $type {
        get => 'Question';
    }

    /**
     * @var array<Link|ActivityPubObject>|null
     */
    #[ObjectArray]
    public ?array $oneOf = null {
        get => $this->oneOf;
        set {
            if ($this->__directSet) {
                $this->oneOf = $value;
            } else {
                $this->set('oneOf', $value);
            }
        }
    }

    /**
     * @var array<Link|ActivityPubObject>|null
     */
    #[ObjectArray]
    public ?array $anyOf = null {
        get => $this->anyOf;
        set {
            if ($this->__directSet) {
                $this->anyOf = $value;
            } else {
                $this->set('anyOf', $value);
            }
        }
    }

    public ActivityPubObject|Link|DateTimeInterface|bool|null $closed = null {
        get => $this->closed;
        set {
            if ($this->__directSet) {
                $this->closed = $value;
            } else {
                $this->set('closed', $value);
            }
        }
    }

    protected function getValidators(): iterable
    {
        yield from parent::getValidators();
        yield from [
            'oneOf' => new ConditionalValidator(
                new NotNull(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(Link::class),
                        new IsInstanceOfValidator(ActivityPubObject::class),
                    ),
                ),
            ),
            'anyOf' => new ConditionalValidator(
                new NotNull(),
                new AllIterableChildrenValidator(
                    new OrValidator(
                        new IsInstanceOfValidator(Link::class),
                        new IsInstanceOfValidator(ActivityPubObject::class),
                    ),
                ),
            ),
        ];
    }
}
