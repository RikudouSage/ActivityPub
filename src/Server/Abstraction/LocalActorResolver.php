<?php

namespace Rikudou\ActivityPub\Server\Abstraction;

interface LocalActorResolver
{
    public function findLocalActorById(string $id): LocalActor;
}
