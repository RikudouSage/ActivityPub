<?php

namespace Rikudou\ActivityPub\Vocabulary\Extensions;

use Rikudou\ActivityPub\ActivityPubConstants;
use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Attribute\SerializedName;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Exception\InvalidOperationException;
use Rikudou\ActivityPub\Validator\CountValidator;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;
use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

/**
 * A non-official extension, allows sending a private message between two actors. The sender is identifier
 * by {@see ChatMessage::$attributedTo} and the receiver by {@see ChatMessage::$to}. There may only be a single
 * receiver in the {@see ChatMessage::$to} array.
 */
class ChatMessage extends BaseObject
{
    public string $type {
        get => 'ChatMessage';
    }

    /**
     * Identifies the context within which the object exists or an activity was performed.
     *
     * The context should always contain {@see ActivityPubConstants::DEFAULT_NAMESPACE} and may include additional
     * contexts.
     *
     * @var array<string|array<string, string>>|string
     */
    #[SerializedName('@context')]
    #[RequiredProperty(ValidatorMode::Lax)]
    public string|array $context = [
        ActivityPubConstants::DEFAULT_NAMESPACE,
        [
            'ChatMessage' => 'http://litepub.social/ns#ChatMessage',
        ],
    ] {
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
     * @internal
     */
    public array|null $bto {
        get => throw new InvalidOperationException('ChatMessage objects can only have a single recipient in the to field.');
        set (ActivityPubObject|Link|array|null|string $value) => throw new InvalidOperationException('ChatMessage objects can only have a single recipient in the to field.');
    }

    /**
     * @internal
     */
    public array|null $cc {
        get => throw new InvalidOperationException('ChatMessage objects can only have a single recipient in the to field.');
        set (ActivityPubObject|Link|array|null|string $value) => throw new InvalidOperationException('ChatMessage objects can only have a single recipient in the to field.');
    }

    /**
     * @internal
     */
    public array|null $bcc {
        get => throw new InvalidOperationException('ChatMessage objects can only have a single recipient in the to field.');
        set (ActivityPubObject|Link|array|null|string $value) => throw new InvalidOperationException('ChatMessage objects can only have a single recipient in the to field.');
    }

    /**
     * @internal
     */
    public Link|ActivityPubObject|array|null $audience {
        get => throw new InvalidOperationException('ChatMessage objects can only have a single recipient in the to field.');
        set (ActivityPubObject|Link|array|null|string $value) => throw new InvalidOperationException('ChatMessage objects can only have a single recipient in the to field.');
    }

    protected function getValidators(): iterable
    {
        yield from parent::getValidators();
        yield from [
            'to' => new CountValidator(1),
        ];
    }
}
