<?php

namespace Rikudou\Tests\ActivityPub\Vocabulary\Core;

use PHPUnit\Framework\Attributes\CoversClass;
use Rikudou\ActivityPub\Exception\InvalidOperationException;
use Rikudou\ActivityPub\Vocabulary\Core\IntransitiveActivity;
use Rikudou\Tests\ActivityPub\AbstractVocabularyTest;

#[CoversClass(IntransitiveActivity::class)]
class IntransitiveActivityTest extends AbstractVocabularyTest
{
    public function testGetObject(): void
    {
        $this->expectException(InvalidOperationException::class);
        $activity = new IntransitiveActivity();
        $activity->object;
    }

    public function testSetObject(): void
    {
        $this->expectException(InvalidOperationException::class);
        $activity = new IntransitiveActivity();
        $activity->object = null;
    }
}
