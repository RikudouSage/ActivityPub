<?php

namespace Rikudou\ActivityPub\Server\ActivitySender;

use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActivity;
use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActor;
use Rikudou\ActivityPub\Vocabulary\Core\Link;

interface ActivitySender
{
    /**
     * @param array<string|Link|ActivityPubActor> $additionalRecipients
     */
    public function send(ActivityPubActivity $activity, array $additionalRecipients = []): void;
}
