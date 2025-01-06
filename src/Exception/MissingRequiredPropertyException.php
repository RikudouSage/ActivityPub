<?php

namespace Rikudou\ActivityPub\Exception;

use RuntimeException;

final class MissingRequiredPropertyException extends RuntimeException implements ActivityPubException
{
}
