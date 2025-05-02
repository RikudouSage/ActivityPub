<?php

namespace Rikudou\ActivityPub\Vocabulary\Core;

use JsonSerializable;
use Rikudou\ActivityPub\ActivityPubConstants;
use Rikudou\ActivityPub\Attribute\IgnoreProperty;
use Rikudou\ActivityPub\Attribute\LangMapProperty;
use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Attribute\SerializedName;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Trait\JsonSerializableObjectTrait;
use Rikudou\ActivityPub\Trait\ObjectSetterTrait;
use Rikudou\ActivityPub\Validator\Condition\NotNull;
use Rikudou\ActivityPub\Validator\ConditionalValidator;
use Rikudou\ActivityPub\Validator\GlobMatchValidator;
use Rikudou\ActivityPub\Validator\LinkRelValidator;
use Rikudou\ActivityPub\Validator\NonNegativeNumberValidator;
use Rikudou\ActivityPub\Validator\UriValidator;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;

/**
 * A Link is an indirect, qualified reference to a resource identified by a URL.
 * The fundamental model for links is established by RFC5988.
 * Many of the properties defined by the Activity Vocabulary allow values that are either instances of {@see BaseObject} or {@see Link}.
 * When a Link is used, it establishes a qualified relation connecting the subject (the containing object) to the resource identified by the href.
 * Properties of the {@see Link} are properties of the reference as opposed to properties of the resource.
 */
class Link implements JsonSerializable
{
    use ObjectSetterTrait;
    use JsonSerializableObjectTrait;

    public string $type {
        get => 'Link';
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

    #[IgnoreProperty]
    public bool $forceRenderingAsLink = false;

    /**
     * The target resource pointed to
     */
    #[RequiredProperty(ValidatorMode::Lax)]
    public ?string $href = null {
        get => $this->href;
        set {
            if ($this->__directSet) {
                $this->href = $value;
            } else {
                $this->set('href', $value);
            }
        }
    }

    /**
     * A link relation associated with a Link. The value must conform to both the HTML5 and RFC5988 "link relation" definitions.
     * @var string|array<string>|null
     */
    public string|array|null $rel = null {
        get => $this->rel;
        set {
            if ($this->__directSet) {
                $this->rel = $value;
            } else {
                $this->set('rel', $value);
            }
        }
    }

    /**
     * Identifies the MIME media type of the referenced resource.
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
     * A simple, human-readable, plain-text name for the object.
     * HTML markup must not be included. The name may be expressed using multiple language-tagged values
     * with the language as a key and value as the content.
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
     * Hints as to the language used by the target resource. Value must be a BCP47 Language-Tag.
     */
    public ?string $hrefLang = null {
        get => $this->hrefLang;
        set {
            if ($this->__directSet) {
                $this->hrefLang = $value;
            } else {
                $this->set('hrefLang', $value);
            }
        }
    }

    /**
     * Specifies a hint as to the rendering height in device-independent pixels of the linked resource.
     */
    public ?int $height = null {
        get => $this->height;
        set {
            if ($this->__directSet) {
                $this->height = $value;
            } else {
                $this->set('height', $value);
            }
        }
    }

    /**
     * Specifies a hint as to the rendering width in device-independent pixels of the linked resource.
     */
    public ?int $width = null {
        get => $this->width;
        set {
            if ($this->__directSet) {
                $this->width = $value;
            } else {
                $this->set('width', $value);
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


    #[IgnoreProperty]
    public bool $simple {
        get => $this->href !== null
            && $this->rel === null
            && $this->mediaType === null
            && $this->name === null
            && $this->hrefLang === null
            && $this->height === null
            && $this->width === null
            && $this->preview === null
            && !$this->forceRenderingAsLink
        ;
    }

    public static function fromString(string $link): Link
    {
        $instance = new self();
        $instance->href = $link;

        return $instance;
    }

    public static function publicAudienceLink(): Link
    {
        $instance = new self();
        $instance->href = ActivityPubConstants::PUBLIC_AUDIENCE;

        return $instance;
    }

    protected function getValidators(): iterable
    {
        return [
            'href' => new ConditionalValidator(
                new NotNull(),
                new UriValidator(),
            ),
            'rel' => new ConditionalValidator(
                new NotNull(),
                new LinkRelValidator(),
            ),
            'mediaType' => new ConditionalValidator(
                new NotNull(),
                new GlobMatchValidator('*/*'),
            ),
            'height' => new ConditionalValidator(
                new NotNull(),
                new NonNegativeNumberValidator(),
            ),
            'width' => new ConditionalValidator(
                new NotNull(),
                new NonNegativeNumberValidator(),
            ),
        ];
    }

    public function __toString(): string
    {
        return $this->href;
    }
}
