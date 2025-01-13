<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use Rikudou\ActivityPub\Enum\PlaceUnit;
use Rikudou\ActivityPub\Validator\Condition\NotNull;
use Rikudou\ActivityPub\Validator\ConditionalValidator;
use Rikudou\ActivityPub\Validator\NonNegativeNumberValidator;
use Rikudou\ActivityPub\Validator\NumberInRangeValidator;
use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

/**
 * Represents a logical or physical location.
 */
class Place extends BaseObject
{
    public string $type {
        get => 'Place';
    }

    /**
     * Indicates the accuracy of position coordinates.
     * Expressed in properties of percentage. e.g. "94.0" means "94.0% accurate".
     */
    public ?float $accuracy = null {
        get => $this->accuracy;
        set {
            if ($this->__directSet) {
                $this->accuracy = $value;
            } else {
                $this->set('accuracy', $value);
            }
        }
    }

    /**
     * Indicates the altitude of a place.
     * The measurement units is indicated using the {@see Place::units} property.
     * If {@see Place::units} is not specified, the default is assumed to be {@see PlaceUnit::Meters}.
     */
    public ?float $altitude = null {
        get => $this->altitude;
        set {
            if ($this->__directSet) {
                $this->altitude = $value;
            } else {
                $this->set('altitude', $value);
            }
        }
    }

    /**
     * The latitude of a place
     */
    public ?float $latitude = null {
        get => $this->latitude;
        set {
            if ($this->__directSet) {
                $this->latitude = $value;
            } else {
                $this->set('latitude', $value);
            }
        }
    }

    /**
     * The longitude of a place
     */
    public ?float $longitude = null {
        get => $this->longitude;
        set {
            if ($this->__directSet) {
                $this->longitude = $value;
            } else {
                $this->set('longitude', $value);
            }
        }
    }

    /**
     * The radius from the given latitude and longitude for a Place.
     * The unit is expressed by the {@see Place::units} property.
     * If {@see Place::units} is not specified, the default is assumed to be {@see PlaceUnit::Meters}.
     */
    public ?float $radius = null {
        get => $this->radius;
        set {
            if ($this->__directSet) {
                $this->radius = $value;
            } else {
                $this->set('radius', $value);
            }
        }
    }

    /**
     * Specifies the measurement units for the {@see Place::radius} and {@see Place::altitude} properties.
     * If not specified, the default is assumed to be {@see PlaceUnit::Meters}.
     */
    public PlaceUnit|Link|null $units = null {
        get => $this->units;
        set (PlaceUnit|Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->units = $value;
            } else {
                $this->set('units', $value);
            }
        }
    }

    protected function getValidators(): iterable
    {
        yield parent::getValidators();
        yield from [
            'accuracy' => new ConditionalValidator(
                new NotNull(),
                new NumberInRangeValidator(0, 100),
            ),
            'radius' => new ConditionalValidator(
                new NotNull(),
                new NonNegativeNumberValidator(),
            ),
        ];
    }
}
