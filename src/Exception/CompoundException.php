<?php

namespace Rikudou\ActivityPub\Exception;

use Exception;
use Throwable;

final class CompoundException extends Exception implements ActivityPubException
{
    /**
     * @param array<Throwable> $exceptions
     */
    public function __construct(
        public readonly array $exceptions,
        string     $message = "",
        int        $code = 0,
        ?Throwable $previous = null,
    ) {
        parent::__construct($message, $code, $previous);
    }
}
