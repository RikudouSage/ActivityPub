<?php

namespace Rikudou\ActivityPub\Vocabulary\Extensions;

use Rikudou\ActivityPub\ActivityPubConstants;
use Rikudou\ActivityPub\Attribute\IgnoreProperty;
use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Attribute\SerializedName;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Exception\InvalidStateException;
use Rikudou\ActivityPub\Validator\CompoundValidator;
use Rikudou\ActivityPub\Validator\StringStartsWithValidator;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

class HashTag extends Link
{
    public string $type {
        get => 'Hashtag';
    }

    #[IgnoreProperty]
    public bool $forceRenderingAsLink {
        get => true;
    }

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
    public string|array $context = [
        ActivityPubConstants::DEFAULT_NAMESPACE,
        [
            'Hashtag' => 'https://www.w3.org/ns/activitystreams#Hashtag',
        ]
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

    protected function getValidators(): iterable
    {
        yield from parent::getValidators();
        yield 'name' => new StringStartsWithValidator('#');
    }

    protected function validateEntity(): void
    {
        if ($this->validatorMode->value >= ValidatorMode::Lax->value) {
            if (!$this->name) {
                throw new InvalidStateException("The name property is required and must be a valid hashtag.");
            }
        }
    }
}
