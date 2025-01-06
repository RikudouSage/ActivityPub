<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Vocabulary\Core\Activity;

/**
 * Indicates that the {@see Add::actor} has added the {@see Add::object} to the {@see Add::target}.
 * If the {@see Add::target} property is not explicitly specified, the target would need to be determined implicitly by context.
 * The {@see Add::origin} can be used to identify the context from which the {@see Add::object} originated.
 */
class Add extends Activity
{
    public string $type {
        get => 'Add';
    }
}
