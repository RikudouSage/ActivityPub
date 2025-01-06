<?php

namespace Rikudou\ActivityPub\Vocabulary\Contract;

use DateInterval;
use DateTimeInterface;
use JsonSerializable;
use Rikudou\ActivityPub\Attribute\LangMapProperty;
use Rikudou\ActivityPub\Dto\NullID;
use Rikudou\ActivityPub\Dto\OmittedID;
use Rikudou\ActivityPub\Vocabulary\Core\Link;
use Rikudou\ActivityPub\Vocabulary\Extended\Object\Image;

interface ActivityPubObject extends JsonSerializable
{
    public string|array $context {
        get;
        set;
    }

    public string $type {
        get;
    }

    public string|null|NullID|OmittedID $id {
        get;
        set;
    }

    /**
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    public ActivityPubObject|Link|array|null $attachment {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * @var Link|ActivityPubObject|array<Link|ActivityPubObject>|null
     */
    public Link|ActivityPubObject|array|null $attributedTo {
        get;
        set (Link|ActivityPubObject|array|null|string $value);
    }

    /**
     * @var Link|ActivityPubObject|array<Link|ActivityPubObject>|null
     */
    public Link|ActivityPubObject|array|null $audience {
        get;
        set (Link|ActivityPubObject|array|null|string $value);
    }

    /**
     * @var string|array<string>|null
     */
    public string|array|null $content {
        get;
        set;
    }

    public DateTimeInterface|null $endTime {
        get;
        set (DateTimeInterface|null|string $value);
    }

    public ActivityPubObject|Link|null $generator {
        get;
        set (ActivityPubObject|Link|null|string $value);
    }

    /**
     * @var Image|Link|array<Image|Link>|null
     */
    public Image|Link|array|null $icon {
        get;
        set (Image|Link|array|null|string $value);
    }

    /**
     * @var Image|Link|array<Image|Link>|null
     */
    public Image|Link|array|null $image {
        get;
        set (Image|Link|array|null|string $value);
    }

    /**
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    public ActivityPubObject|Link|array|null $inReplyTo {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    public ActivityPubObject|Link|array|null $location {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    public ActivityPubObject|Link|null $preview {
        get;
        set (ActivityPubObject|Link|null|string $value);
    }

    public DateTimeInterface|null $published {
        get;
        set (DateTimeInterface|null|string $value);
    }

    public ActivityPubCollection|null $replies {
        get;
        set;
    }

    public DateTimeInterface|null $startTime {
        get;
        set (DateTimeInterface|null|string $value);
    }

    #[LangMapProperty]
    public string|array|null $summary {
        get;
        set;
    }

    /**
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    public ActivityPubObject|Link|array|null $tag {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    public DateTimeInterface|null $updated {
        get;
        set (DateTimeInterface|null|string $value);
    }

    /**
     * @var Link|array<Link>|null
     */
    public Link|array|null $url {
        get;
        set (Link|array|null|string $value);
    }

    public ?array $to {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * @var array<ActivityPubObject|Link>|null
     */
    public array|null $bto {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * @var array<ActivityPubObject|Link>|null
     */
    public array|null $cc {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    /**
     * @var array<ActivityPubObject|Link>|null
     */
    public array|null $bcc {
        get;
        set (ActivityPubObject|Link|array|null|string $value);
    }

    public ?string $mediaType {
        get;
        set;
    }

    public ?DateInterval $duration {
        get;
        set (DateInterval|null|string $value);
    }
}
