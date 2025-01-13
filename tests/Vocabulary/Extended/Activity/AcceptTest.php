<?php

namespace Rikudou\Tests\ActivityPub\Vocabulary\Extended\Activity;

use PHPUnit\Framework\Attributes\CoversClass;
use PHPUnit\Framework\Attributes\DataProvider;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Exception\InvalidPropertyValueException;
use Rikudou\ActivityPub\Vocabulary\Core\Activity;
use Rikudou\ActivityPub\Vocabulary\Core\Link;
use Rikudou\ActivityPub\Vocabulary\Extended\Activity\Accept;
use Rikudou\ActivityPub\Vocabulary\Extended\Activity\Follow;
use Rikudou\ActivityPub\Vocabulary\Extended\Actor\Person;
use Rikudou\ActivityPub\Vocabulary\Extended\Object\Place;
use Rikudou\Tests\ActivityPub\AbstractVocabularyTest;

#[CoversClass(Accept::class)]
final class AcceptTest extends AbstractVocabularyTest
{
    #[DataProvider('validationData')]
    public function testValidations(ValidatorMode $validatorMode, mixed $objectValue, bool $expectException): void
    {
        $accept = $this->createMinimalValidObject(Accept::class, $validatorMode);
        $accept->validatorMode = $validatorMode;
        if ($expectException) {
            $this->expectException(InvalidPropertyValueException::class);
        }
        $accept->object = $objectValue;

        self::assertIsArray($accept->jsonSerialize());
    }

    public static function validationData(): iterable
    {
        yield [ValidatorMode::None, self::createMinimalValidObject(Place::class), false];
        yield [ValidatorMode::Lax, self::createMinimalValidObject(Place::class), false];
        yield [ValidatorMode::Strict, self::createMinimalValidObject(Place::class), false];

        yield [ValidatorMode::Recommended, self::createMinimalValidObject(Place::class), true];
        yield [ValidatorMode::Recommended, self::createMinimalValidObject(Person::class), true];
        yield [ValidatorMode::Recommended, [self::createMinimalValidObject(Place::class)], true];
        yield [ValidatorMode::Recommended, null, true];
        yield [ValidatorMode::Recommended, [self::createMinimalValidObject(Follow::class)], true];
        yield [ValidatorMode::Recommended, [self::createMinimalValidObject(Link::class)], true];

        yield [ValidatorMode::Recommended, self::createMinimalValidObject(Link::class), false];
        yield [ValidatorMode::Recommended, self::createMinimalValidObject(Follow::class), false];
        yield [ValidatorMode::Recommended, self::createMinimalValidObject(Activity::class), false];
    }
}
