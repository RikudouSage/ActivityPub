<?php

namespace Rikudou\ActivityPub\Vocabulary\Core;

use Rikudou\ActivityPub\Attribute\ObjectArray;
use Rikudou\ActivityPub\Exception\InvalidOperationException;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubIntransitiveActivity;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;

/**
 * Instances of IntransitiveActivity are a subtype of {@see Activity} representing intransitive actions.
 * The object property is therefore inappropriate for these activities.
 */
class IntransitiveActivity extends Activity implements ActivityPubIntransitiveActivity
{
    public string $type {
        get => 'IntransitiveActivity';
    }

    /**
     * @internal
     * @var ActivityPubObject|Link|array<ActivityPubObject|Link>|null
     */
    #[ObjectArray]
    public ActivityPubObject|Link|array|null $object = null {
        get {
            throw new InvalidOperationException('Intransitive activities cannot have objects');
        }
        set (ActivityPubObject|Link|array|null|string $value) {
            throw new InvalidOperationException('Intransitive activities cannot have objects');
        }
    }
}
