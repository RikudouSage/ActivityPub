<?php

namespace Rikudou\ActivityPub\Server\ActivitySender;

use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActivity;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

interface ActivitySender
{
    /**
     * @param array<string|Link|ActivityPubActor> $additionalRecipients
     * @param (callable(string $inboxUrl): bool)|null $receiverFilter
     */
    public function send(ActivityPubActivity $activity, array $additionalRecipients = [], ?callable $receiverFilter = null): void;
}
