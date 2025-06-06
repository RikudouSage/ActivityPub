<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Validator\Condition\AndCondition;
use Rikudou\ActivityPub\Validator\Condition\IsInstanceOf;
use Rikudou\ActivityPub\Validator\Condition\IsValidatorMode;
use Rikudou\ActivityPub\Validator\Condition\NotCondition;
use Rikudou\ActivityPub\Validator\ConditionalValidator;
use Rikudou\ActivityPub\Validator\IsInstanceOfValidator;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActivity;
use Rikudou\ActivityPub\Vocabulary\Core\Activity;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

/**
 * Indicates that the {@see Undo::actor} is undoing the {@see Undo::object}.
 * In most cases, the object will be an {@see Activity} describing some previously performed action
 * (for instance, a person may have previously "liked" an article but, for whatever reason, might choose to undo that like at some later point in time).
 *
 * The {@see Undo::target} and {@see Undo::origin} typically have no defined meaning.
 */
class Undo extends Activity
{
    public string $type {
        get => 'Undo';
    }

    protected function getValidators(): iterable
    {
        yield from parent::getValidators();
        yield from [
            'object' => new ConditionalValidator(
                new AndCondition(
                    new IsValidatorMode(ValidatorMode::Recommended),
                    new NotCondition(new IsInstanceOf(Link::class)),
                ),
                new IsInstanceOfValidator(ActivityPubActivity::class),
            ),
        ];
    }
}
