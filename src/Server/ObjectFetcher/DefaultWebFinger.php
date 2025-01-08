<?php

namespace Rikudou\ActivityPub\Server\ObjectFetcher;

use Psr\Http\Client\ClientExceptionInterface;
use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Rikudou\ActivityPub\Dto\WebFingerLink;
use Rikudou\ActivityPub\Dto\WebFingerResponse;
use Rikudou\ActivityPub\Exception\WebFingerException;

final readonly class DefaultWebFinger implements WebFinger
{
    public function __construct(
        private RequestFactoryInterface $requestFactory,
        private ClientInterface $httpClient,
    ) {
    }

    public function find(string $account): WebFingerResponse
    {
        if (!str_starts_with($account, 'acct:')) {
            $account = "acct:{$account}";
        }

        $parts = explode('@', $account);
        $domain = $parts[1];

        $request = $this->requestFactory->createRequest(
            'GET',
            "https://{$domain}/.well-known/webfinger?resource={$account}"
        )->withHeader('Accept', 'application/jrd+json');

        try {
            $response = $this->httpClient->sendRequest($request);
            if ($response->getStatusCode() < 200 || $response->getStatusCode() > 299) {
                throw new WebFingerException("Received an invalid status code from WebFinger: {$response->getStatusCode()}");
            }

            $json = json_decode((string) $response->getBody(), true, flags: JSON_THROW_ON_ERROR);

            return new WebFingerResponse(
                subject: $json['subject'] ?? throw new WebFingerException('The subject is missing'),
                aliases: $json['aliases'] ?? null,
                properties: $json['properties'] ?? null,
                links: array_map(
                    fn (array $link) => new WebFingerLink(
                        rel: $link['rel'] ?? throw new WebFingerException('The link rel is missing'),
                        href: $link['href'] ?? throw new WebFingerException('The link href is missing'),
                        type: $link['type'] ?? null,
                        titles: $link['titles'] ?? null,
                        properties: $link['properties'] ?? null,
                    ),
                    $json['links'] ?? []
                )
            );
        } catch (ClientExceptionInterface $e) {
            throw new WebFingerException("Failed retrieving webfinger for account '{$account}': {$e->getMessage()}", previous: $e);
        }
    }
}
