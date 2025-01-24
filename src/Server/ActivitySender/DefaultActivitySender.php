<?php

namespace Rikudou\ActivityPub\Server\ActivitySender;

use Psr\Http\Client\ClientInterface;
use Psr\Http\Message\RequestFactoryInterface;
use Psr\Http\Message\StreamFactoryInterface;
use Rikudou\ActivityPub\ActivityPubConstants;
use Rikudou\ActivityPub\Dto\Endpoints;
use Rikudou\ActivityPub\Exception\ActivityPubException;
use Rikudou\ActivityPub\Exception\CompoundException;
use Rikudou\ActivityPub\Exception\InvalidOperationException;
use Rikudou\ActivityPub\Exception\InvalidValueException;
use Rikudou\ActivityPub\Exception\UnhandledOutgoingActivityException;
use Rikudou\ActivityPub\Server\Abstraction\LocalActor;
use Rikudou\ActivityPub\Server\Abstraction\LocalActorResolver;
use Rikudou\ActivityPub\Server\CollectionResolver\CollectionResolver;
use Rikudou\ActivityPub\Server\ObjectFetcher\ObjectFetcher;
use Rikudou\ActivityPub\Server\Signing\RequestSigner;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActivity;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubCollection;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

final readonly class DefaultActivitySender implements ActivitySender
{
    public function __construct(
        private ObjectFetcher $objectFetcher,
        private CollectionResolver $collectionResolver,
        private RequestFactoryInterface $requestFactory,
        private ClientInterface $httpClient,
        private StreamFactoryInterface $streamFactory,
        private RequestSigner $requestSigner,
        private ?LocalActorResolver $localActorResolver = null,
    ) {
    }

    /**
     * @param array<string|Link|ActivityPubActor> $additionalRecipients
     * @param (callable(string $inboxUrl): bool)|null $receiverFilter
     */
    public function send(
        ActivityPubActivity $activity,
        array $additionalRecipients = [],
        ?callable $receiverFilter = null,
        ?LocalActor $localActor = null,
    ): void {
        $receiverFilter ??= fn (string $inboxUrl): true => true;

        $recipients = [
            ...$additionalRecipients,
            ...($activity->to ?? []),
            ...($activity->cc ?? []),
            ...($activity->bto ?? []),
            ...($activity->bcc ?? []),
        ];
        $activity->bto = null;
        $activity->bcc = null;

        $exceptions = [];
        if (is_array($activity->actor)) {
            throw new InvalidOperationException("Currently cannot send activities with multiple actors, please implement that on your own.");
        }
        if (!$activity->actor) {
            throw new InvalidValueException("Every action must have an actor.");
        }

        $localActor ??= $this->localActorResolver?->findLocalActorById(
            $activity->actor instanceof ActivityPubActor
                ? $activity->actor->id
                : $activity->actor
        );
        if ($localActor === null) {
            throw new InvalidOperationException("You must either provide the local actor manually, or provide a service implementing LocalActorResolver.");
        }

        $handled = [];
        $recipients = $this->getRecipientInboxes($recipients, $localActor);
        foreach ($recipients as $recipient) {
            if (isset($handled[$recipient])) {
                continue;
            }
            if (!$receiverFilter($recipient)) {
                continue;
            }
            $this->sendSingle($activity, $recipient, $localActor, $exceptions);
            $handled[$recipient] = true;
        }

        if (count($exceptions)) {
            throw new CompoundException($exceptions, 'Multiple exceptions have been thrown');
        }
    }

    private function sendSingle(ActivityPubActivity $activity, string $recipient, LocalActor $localActor, array &$exceptions): void
    {
        $request = $this->requestFactory->createRequest('POST', $recipient)
            ->withBody($this->streamFactory->createStream(json_encode($activity)))
            ->withHeader('Accept', 'application/activity+json')
        ;
        $request = $this->requestSigner->signRequest(
            request: $request,
            actor: $localActor,
        );
        $response = $this->httpClient->sendRequest($request);

        if ($response->getStatusCode() < 200 || $response->getStatusCode() >= 300) {
            $errorMessage = "Invalid response: {$response->getStatusCode()} ({$response->getBody()})";
            $exceptions[] = new UnhandledOutgoingActivityException($activity, $errorMessage);
        }
    }

    /**
     * @param array<string|Link|ActivityPubActor> $recipients
     * @return array<string|Link>
     */
    private function getRecipientInboxes(array $recipients, LocalActor $actor): iterable
    {
        foreach ($recipients as $recipient) {
            if ($recipient instanceof ActivityPubActor) {
                if ($recipient->endpoints instanceof Endpoints && $recipient->endpoints->sharedInbox) {
                    yield $recipient->endpoints->sharedInbox;
                }
                if ($recipient->inbox) {
                    yield $recipient->inbox;
                }
            } else if ((string) $recipient !== ActivityPubConstants::PUBLIC_AUDIENCE) {
                try {
                    $object = $this->objectFetcher->fetch($recipient, true, $actor);
                    if ($object instanceof ActivityPubActor) {
                        if ($object->endpoints instanceof Endpoints && $object->endpoints->sharedInbox) {
                            yield $object->endpoints->sharedInbox;
                        } else if ($object->inbox) {
                            yield $object->inbox;
                        }
                    } else if ($object instanceof ActivityPubCollection) {
                        foreach ($this->collectionResolver->resolve($object) as $item) {
                            if ($item instanceof ActivityPubActor) {
                                if ($item->endpoints instanceof Endpoints && $item->endpoints->sharedInbox) {
                                    yield $item->endpoints->sharedInbox;
                                } else if ($item->inbox) {
                                    yield $item->inbox;
                                }
                            }
                        }
                    }
                } catch (ActivityPubException) {
                   // ignore
                }
            }
        }
    }
}
