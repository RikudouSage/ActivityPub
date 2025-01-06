<?php

namespace Rikudou\ActivityPub\Exception;

use LogicException;

final class InvalidStateException extends LogicException implements ActivityPubException
{
}
