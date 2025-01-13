<?php

namespace Rikudou\Tests\ActivityPub;

use PHPUnit\Framework\TestCase;
use Rikudou\ActivityPub\Dto\PublicKey;
use Rikudou\ActivityPub\Enum\ValidatorMode;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActivity;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;
use Rikudou\ActivityPub\Vocabulary\Core\CollectionPage;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

abstract class AbstractVocabularyTest extends TestCase
{
    /**
     * @template T of ActivityPubObject|Link
     *
     * @param class-string<T> $class
     * @return T
     */
    protected static function createMinimalValidObject(string $class, ValidatorMode $validatorMode = ValidatorMode::Recommended): object
    {
        $object = new $class();

        if ($validatorMode->value >= ValidatorMode::Lax->value) {
            if ($object instanceof ActivityPubActor) {
                $object->inbox = 'https://example.com/inbox/1';
            }
            if ($object instanceof Link) {
                $object->href = 'https://example.com/some-link-address';
            }
        }
        if ($validatorMode->value >= ValidatorMode::Strict->value) {
            if ($object instanceof ActivityPubObject) {
                $object->id = 'https://example.com/id/1';
            }
            if ($object instanceof ActivityPubActor) {
                $object->outbox = 'https://example.com/outbox/1';
                $object->following = 'https://example.com/following/1';
                $object->followers = 'https://example.com/followers/1';
            }
        }
        if ($validatorMode->value >= ValidatorMode::Recommended->value) {
            if ($object instanceof ActivityPubActivity) {
                $object->actor = 'https://example.com/actor/1';
                $object->object = 'https://example.com/object/1';
            }
            if ($object instanceof ActivityPubActor) {
                $object->preferredUsername = 'someone';
                $object->publicKey = new PublicKey(
                    id: 'https://example.com/id/1#key',
                    owner: $object->id,
                    publicKeyPem: 'TODO',
                );
            }
            if ($object instanceof CollectionPage) {
                $object->partOf = 'https://example.com/partOf/1';
            }
        }

        return $object;
    }
}
