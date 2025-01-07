<?php

namespace Rikudou\ActivityPub\Server;

use DateTimeImmutable;
use DateTimeZone;
use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\RequestInterface;
use Psr\Http\Message\ServerRequestInterface;
use Rikudou\ActivityPub\Exception\CryptographyException;
use Rikudou\ActivityPub\Exception\InvalidOperationException;
use Rikudou\ActivityPub\Exception\InvalidStateException;
use Rikudou\ActivityPub\Exception\InvalidValueException;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;
use Rikudou\ActivityPub\Vocabulary\Parser\DefaultTypeParser;
use Rikudou\ActivityPub\Vocabulary\Parser\TypeParser;
use SensitiveParameter;

final readonly class RequestSignerAndValidator implements RequestSigner, RequestValidator
{
    private const string SUPPORTED_ALGORITHM = 'hs2019';

    public function __construct(
        private ClientInterface $httpClient,
        private RequestFactoryInterface $requestFactory,
        private TypeParser $typeParser = new DefaultTypeParser(),
    ) {
    }

    public function signRequest(RequestInterface $request, string $keyId, #[SensitiveParameter] string $privateKeyPem): RequestInterface
    {
        $headers = $this->createHeaders($request);
        $signingParts = [];
        foreach ($headers as $headerName => $value) {
            $signingParts[] = "{$headerName}: {$value}";
            if (!fnmatch('(*)', $headerName)) {
                $request = $request->withHeader($headerName, $value);
            }
        }
        $stringToSign = implode("\n", $signingParts);

        $privateKey = openssl_pkey_get_private($privateKeyPem) ?: throw new CryptographyException('Private key is not valid');
        $success = openssl_sign($stringToSign, $signature, $privateKey, OPENSSL_ALGO_SHA256);
        if (!$success) {
            throw new CryptographyException('Failed to create signature');
        }

        $signatureHeader = sprintf(
            'keyId="%s",algorithm="%s",headers="%s",signature="%s"',
            $keyId,
            self::SUPPORTED_ALGORITHM,
            implode(' ', array_keys($headers)),
            base64_encode($signature),
        );

        $request = $request->withHeader('Signature', $signatureHeader);

        return $request;
    }

    public function isRequestValid(ServerRequestInterface $request): bool
    {
        $signatureHeader = $request->getHeader('Signature');
        if (!$signatureHeader) {
            return false;
        }
        $signatureHeader = $signatureHeader[0];

        $parsed = $this->parseSignature($signatureHeader);
        if (!$parsed) {
            return false;
        }

        $keyId = $parsed['keyId'] ?? null;
        $algorithm = strtolower($parsed['algorithm'] ?? '');
        if ($algorithm !== self::SUPPORTED_ALGORITHM) {
            throw new InvalidOperationException('Cannot verify an unsupported algorithm: ' . $algorithm);
        }

        $headers = isset($parsed['headers']) ? explode(' ', $parsed['headers']) : null;
        $signature = isset($parsed['signature']) ? base64_decode($parsed['signature']) : null;

        if (!$keyId || !$algorithm || !$headers || !$signature) {
            return false;
        }

        $signingString = $this->buildSigningString($headers, $request);
        if (!$signingString) {
            return false;
        }

        $opensslAlgorithm = OPENSSL_ALGO_SHA256;
        $publicKey = $this->fetchPublicKey($keyId);

        return openssl_verify(
            $signingString,
            $signature,
            $publicKey,
            $opensslAlgorithm,
        ) === 1;
    }

    /**
     * @return array<string, string>
     */
    private function createHeaders(RequestInterface $request): array
    {
        $inboxPath = $request->getUri()->getPath();
        if ($query = $request->getUri()->getQuery()) {
            $inboxPath .= '?' . $query;
        }

        $body = (string) $request->getBody();
        if (!$body) {
            throw new InvalidStateException('The request must already have the final body assigned');
        }

        return [
            '(request-target)' => "post {$inboxPath}",
            'content-type' => 'application/activity+json',
            'date' => new DateTimeImmutable()->setTimezone(new DateTimeZone('UTC'))->format(DateTimeImmutable::RFC7231),
            'digest' => 'SHA-256=' . base64_encode(hash('sha256', $body, true)),
            'host' => $request->getUri()->getHost(),
        ];
    }

    /**
     * @return array{keyId?: string, algorithm?: string, headers?: string, signature?: string}
     */
    private function parseSignature(string $signature): array
    {
        $parts = explode(',', $signature);
        $map = [];
        foreach ($parts as $part) {
            $keyValue = explode('=', trim($part), 2);
            if (count($keyValue) !== 2) {
                continue;
            }
            $key = $keyValue[0];
            $value = trim($keyValue[1], '"'); // remove surrounding quotes
            $map[$key] = $value;
        }

        if (!isset($map['keyId'], $map['algorithm'], $map['headers'], $map['signature'])) {
            return [];
        }

        return $map;
    }

    /**
     * @param array<string> $headers
     */
    private function buildSigningString(array $headers, RequestInterface $request): ?string
    {
        $signingParts = [];
        foreach ($headers as $headerName) {
            if ($headerName === '(request-target)') {
                $path = $request->getUri()->getPath();
                if ($query = $request->getUri()->getQuery()) {
                    $path .= '?' . $query;
                }
                $value = sprintf('%s %s', strtolower($request->getMethod()), $path);
                $signingParts[] = '(request-target): ' . $value;
            } else {
                $value = $request->getHeader($headerName);
                if (!$value) {
                    return null;
                }
                $signingParts[] = strtolower($headerName) . ': ' . $value[0];
            }
        }

        return  implode("\n", $signingParts);
    }

    private function fetchPublicKey(string $keyId): string
    {
        $request = $this->requestFactory
            ->createRequest('GET', $keyId)
            ->withHeader('Accept', 'application/activity+json')
        ;
        try {
            $response = $this->httpClient->sendRequest($request);
        } catch (ClientExceptionInterface $e) {
            throw new InvalidValueException('Failed fetching public key from the remote actor', previous: $e);
        }
        if ($response->getStatusCode() !== 200) {
            throw new InvalidValueException('Failed fetching public key from the remote actor');
        }

        $object = $this->typeParser->parseJson($response->getBody());
        if (!$object instanceof ActivityPubActor) {
            throw new InvalidValueException('The fetched resource is not a valid actor');
        }

        if (!$object->publicKey) {
            throw new InvalidValueException('The fetched resource is an actor without a public key specified');
        }

        return $object->publicKey->publicKeyPem;
    }
}
