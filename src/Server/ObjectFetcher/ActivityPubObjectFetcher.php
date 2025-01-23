<?php

namespace Rikudou\ActivityPub\Server\ObjectFetcher;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Rikudou\ActivityPub\Dto\WebFingerResponse;
use Rikudou\ActivityPub\Exception\InvalidValueException;
use Rikudou\ActivityPub\Exception\ResourceException;
use Rikudou\ActivityPub\Server\Abstraction\LocalActor;
use Rikudou\ActivityPub\Server\Signing\RequestSigner;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubObject;
use Rikudou\ActivityPub\Vocabulary\Core\Link;
use Rikudou\ActivityPub\Vocabulary\Parser\TypeParser;

final readonly class ActivityPubObjectFetcher implements ObjectFetcher
{
    public function __construct(
        private RequestFactoryInterface $requestFactory,
        private ClientInterface $httpClient,
        private TypeParser $typeParser,
        private RequestSigner $requestSigner,
    ) {
    }

    public function fetch(string|Link|WebFingerResponse $url, bool $allowCustomProperties = false, ?LocalActor $actor = null): ActivityPubObject
    {
        if ($url instanceof Link) {
            $url = (string) $url;
        } else if ($url instanceof WebFingerResponse) {
            foreach ($url->links as $link) {
                if ($link->type !== 'application/activity+json') {
                    continue;
                }

                $url = $link->href;
                break;
            }

            if (!is_string($url)) {
                throw new InvalidValueException("The webfinger response does not contain any link with the application/activity+json type");
            }
        }

        $request = $this->requestFactory->createRequest('GET', $url)
            ->withHeader('Accept', 'application/activity+json')
        ;

        if ($actor) {
            $request = $this->requestSigner->signRequest($request, $actor);
        }

        try {
            $response = $this->httpClient->sendRequest($request);
            if ($response->getStatusCode() !== 200) {
                throw new ResourceException("The resource at {$url} did not respond with a valid status code, got {$response->getStatusCode()}");
            }

            return $this->typeParser->parseJson($response->getBody(), $allowCustomProperties);
        } catch (ClientExceptionInterface $e) {
            throw new ResourceException("Got an error while fetching resource from {$url}: {$e->getMessage()}", previous: $e);
        }
    }
}
