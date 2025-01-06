<?php

namespace Rikudou\ActivityPub\Vocabulary\Extended\Activity;

use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Validator\Condition\AndCondition;
use Rikudou\ActivityPub\Validator\Condition\IsInstanceOf;
use Rikudou\ActivityPub\Validator\Condition\IsValidatorMode;
use Rikudou\ActivityPub\Validator\Condition\NotCondition;
use Rikudou\ActivityPub\Validator\ConditionalValidator;
use Rikudou\ActivityPub\Validator\IsInstanceOfValidator;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;
use Rikudou\ActivityPub\Vocabulary\Core\Activity;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

/**
 * Indicates that the {@see Follow::actor} is "following" the {@see Follow::object}.
 * Following is defined in the sense typically used within Social systems in which the actor is interested in any activity
 * performed by or on the object. The {@see Follow::target} and {@see Follow::origin} typically have no defined meaning.
 */
class Follow extends Activity
{
    public string $type {
        get => 'Follow';
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
                new IsInstanceOfValidator(ActivityPubActor::class),
            ),
        ];
    }
}
