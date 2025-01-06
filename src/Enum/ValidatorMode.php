<?php

namespace Rikudou\ActivityPub\Enum;

enum ValidatorMode: int
{
    case Recommended = 3;
    case Strict = 2;
    case Lax = 1;
    case None = 0;
}
