<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see Update::actor} has updated the {@see Update::object}.
 * Note, however, that this vocabulary does not define a mechanism for describing the actual set of modifications made to object.
 *
 * The {@see Update::target} and {@see Update::origin} typically have no defined meaning.
 */
class Update extends Activity
{
    public string $type {
        get => 'Update';
    }
}
