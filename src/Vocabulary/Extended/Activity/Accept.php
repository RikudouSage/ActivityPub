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
 * Indicates that the {@see Accept::actor} accepts the {@see Accept::object}.
 * The {@see Accept::target} property can be used in certain circumstances to indicate the context into which the {@see Accept::object} has been accepted.
 */
class Accept extends Activity
{
    public string $type {
        get => 'Accept';
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
