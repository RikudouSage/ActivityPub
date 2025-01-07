<?php

namespace Rikudou\ActivityPub\Vocabulary\Extensions;

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
