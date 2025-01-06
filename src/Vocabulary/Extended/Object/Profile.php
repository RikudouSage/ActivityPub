<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Object;

use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;
use Rikudou\ActivityPub\Vocabulary\Core\Actor;
use Rikudou\ActivityPub\Vocabulary\Core\BaseObject;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

/**
 * A Profile is a content object that describes another {@see BaseObject}, typically used to describe {@see Actor} objects.
 * The {@see Profile::describes} property is used to reference the object being described by the profile.
 */
class Profile extends BaseObject
{
    public string $type {
        get => 'Profile';
    }

    /**
     * Identifies the object described by the Profile.
     */
    public ActivityPubObject|Link|null $describes = null {
        get => $this->describes;
        set (ActivityPubObject|Link|null|string $value) {
            if (is_string($value)) {
                $value = Link::fromString($value);
            }

            if ($this->__directSet) {
                $this->describes = $value;
            } else {
                $this->set('describes', $value);
            }
        }
    }
}
