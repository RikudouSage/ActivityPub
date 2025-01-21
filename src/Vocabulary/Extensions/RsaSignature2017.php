<?php

namespace Rikudou\ActivityPub\Vocabulary\Extensions;

use DateTimeImmutable;
use DateTimeInterface;
use Rikudou\ActivityPub\Attribute\RequiredProperty;
use Rikudou\ActivityPub\Dto\NullID;
use Rikudou\ActivityPub\Dto\OmittedID;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Exception\InvalidPropertyValueException;
use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

final class RsaSignature2017 extends BaseObject
{
    public string $type {
        get => 'RsaSignature2017';
    }

    public string|null|NullID|OmittedID $id = null {
        get => throw new InvalidPropertyValueException("RsaSignature2017 does not have an ID");
        set => throw new InvalidPropertyValueException("RsaSignature2017 does not have an ID");
    }

    #[RequiredProperty(ValidatorMode::Lax)]
    public ?Link $creator = null {
        get => $this->creator;
        set (Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->creator = $value;
            } else {
                $this->set('creator', $value);
            }
        }
    }

    #[RequiredProperty(ValidatorMode::Lax)]
    public ?DateTimeInterface $created = null {
        get => $this->created;
        set (DateTimeInterface|null|string $value) {
            if (is_string($value)) {
                $value = new DateTimeImmutable($value);
            }

            if ($this->__directSet) {
                $this->created = $value;
            } else {
                $this->set('created', $value);
            }
        }
    }

    #[RequiredProperty(ValidatorMode::Lax)]
    public ?string $signatureValue = null {
        get => $this->signatureValue;
        set {
            if ($this->__directSet) {
                $this->signatureValue = $value;
            } else {
                $this->set('signatureValue', $value);
            }
        }
    }
}
