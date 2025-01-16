<?php

namespace Rikudou\ActivityPub\Server\ActivitySender;

use Rikudou\ActivityPub\Exception\CompoundException;
use Rikudou\ActivityPub\Server\Abstraction\LocalActor;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActivity;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

interface ActivitySender
{
    /**
     * @param array<string|Link|ActivityPubActor> $additionalRecipients
     * @param (callable(string $inboxUrl): bool)|null $receiverFilter
     *
     * @throws CompoundException
     */
    public function send(
        ActivityPubActivity $activity,
        array $additionalRecipients = [],
        ?callable $receiverFilter = null,
        ?LocalActor $localActor = null,
    ): void;
}
