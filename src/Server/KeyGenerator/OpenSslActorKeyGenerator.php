<?php

namespace Rikudou\ActivityPub\Server\KeyGenerator;

use Rikudou\ActivityPub\Dto\KeyPair;
use Rikudou\ActivityPub\Dto\PublicKey;
use Rikudou\ActivityPub\Exception\CryptographyException;
use Rikudou\ActivityPub\Server\KeyGenerator\ActorKeyGenerator;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;

final readonly class OpenSslActorKeyGenerator implements ActorKeyGenerator
{
    public function generate(?ActivityPubActor $actor = null, int $bits = 4096): KeyPair
    {
        $config = [
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'private_key_bits' => $bits,
        ];
        $resource = openssl_pkey_new($config) ?: throw new CryptographyException('Failed generating new private key');

        $privateKeyPem = '';
        openssl_pkey_export($resource, $privateKeyPem);
        assert(is_string($privateKeyPem));

        $details = openssl_pkey_get_details($resource) ?: throw new CryptographyException('Failed decoding the private key');
        $publicKeyPem = $details['key'];
        assert(is_string($publicKeyPem));

        $keyPair = new KeyPair($privateKeyPem, $publicKeyPem);
        $actor->publicKey = new PublicKey(
            id: $actor->id . '#mainKey',
            owner: $actor->id,
            publicKeyPem: $keyPair->publicKey,
        );

        return $keyPair;
    }
}
