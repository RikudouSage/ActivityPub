<?php

namespace Rikudou\ActivityPub\Exception;

use Rikudou\ActivityPub\Vocabulary\Contract\ActivityPubActivity;
use RuntimeException;
use Throwable;

final class UnhandledOutgoingActivityException extends RuntimeException implements ActivityPubException
{
    public function __construct(
        public readonly ActivityPubActivity $unhandledActivity,
        string                              $message = "",
        int                                 $code = 0,
        ?Throwable                          $previous = null
    ) {
        parent::__construct($message, $code, $previous);
    }
}
