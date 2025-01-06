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
 * Indicates that the {@see Announce::actor} is calling the {@see Announce::target}'s attention the {@see Announce::object}.
 *
 * The {@see Announce::origin} typically has no defined meaning.
 */
class Announce extends Activity
{
    public string $type {
        get => 'Announce';
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
