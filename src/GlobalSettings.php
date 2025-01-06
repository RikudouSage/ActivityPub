<?php

namespace Rikudou\ActivityPub;

use Rikudou\ActivityPub\Enum\ValidatorMode;

final class GlobalSettings
{
    public static ValidatorMode $validatorMode = ValidatorMode::Strict;

    private function __construct()
    {
    }
}
